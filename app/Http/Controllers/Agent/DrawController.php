<?php


namespace App\Http\Controllers\Agent;


use App\Http\Requests\StoreRequest;
use App\Models\Agcount;
use App\Models\Bank;
use App\Models\Billflow;
use App\Models\Draw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class DrawController extends BaseController
{
    /**
     * 数据列表
     */
    public function index(Request $request){
        $map = array();
        $id = Auth::id();
        $map['agent_id']=$id;
        if(true==$request->has('status')){
            $map['status']=$request->input('status');
        }
        $data = Draw::where($map)->orderBy('creatime','desc')->paginate(10)->appends($request->all());
        foreach ($data as $key =>$value){
            $data[$key]['money']=$data[$key]['money']/100;
            $data[$key]['creatime'] =date("Y-m-d H:i:s",$value["creatime"]);
            if($data[$key]['endtime']!=0){
                $data[$key]['endtime'] = date("Y-m-d H:i:s",$value["endtime"]);
            }else{
                $data[$key]['endtime'] = "";
            }
            $data[$key]['feemoney']=$data[$key]['feemoney']/100;
        }
        return view('draw.list',['list'=>$data,'input'=>$request->all()]);
    }
    /**
     * 编辑
     */
    public function edit($id=0){
        $info = $id?Draw::find($id):[];
        //获取当前登陆用户的银行卡列表
        $id = Auth::id();
        $data = Bank::get()->where('agent_id',$id)->where('status',1);
        //获取当前登陆用户余额
        $ageCount = Agcount::where('agent_id',$id)->first();
        $ageCount['balance'] = $ageCount['balance']/100;
        //获取提现手继续
        $fee = DB::table('admin_options')->where('key','=','one_time_draw')->value('value');
        return view('draw.edit',['info'=>$info,'id'=>$id,'banklist'=>$data,'balance'=>$ageCount,'fee'=>$fee/100]);
    }
    /**
     * 保存数据
     */
    public function store(StoreRequest $request){
        $order_sn = date("YmdHis",time()).mt_rand(100000, 999999);
        //获取银行卡id
        $bankid = $request->input('bank_card');
        //获取银行卡信息
        $bankInfo = $bankid?Bank::find($bankid):[];
        //获取当前登陆用户的id
        $id = Auth::id();
        //获取当前的用户余额
        $agCount = Agcount::where('agent_id',$id)->first();
        //获取用户信息
        $userInfo = $id?User::find($id):[];
        //获取提现手继续
        $fee = DB::table('admin_options')->where('key','=','one_time_draw')->value('value');
        if($request->input('money')*100+$fee>$agCount['balance']){
            return ['msg'=>'余额不足！不能提现！','status'=>0];
        }else if(md5(md5($request->input('paypassword')))!=$userInfo['pay_pass']){
            return ['msg'=>'提现密码不正确！','status'=>0];
        }else{
            $lock = $this->lock($id);
            if($lock==true){
                //开启事物
                DB::beginTransaction();
                try{
                    $agCon = Agcount::onWriteConnection()->where('agent_id',$id)->lockForUpdate()->first();
                    if($request->input('money')*100>$agCon['balance']){
                        $this->unlock($id);
                        return ['msg'=>'您输入的金额大于余额！请重新输入','status'=>0];
                    }else{
                       $num= Agcount::where('agent_id',$id)->decrement('balance',(int)$request->input('money')*100);
                       if($num){
                           $count = Draw::insert(['agent_id'=>$id,'order_sn'=>$order_sn,'name'=>$bankInfo['name'],'deposit_name'=>$bankInfo['deposit_name'],'deposit_card'=>$bankInfo['deposit_card'],'money'=>$request->input('money')*100,'creatime'=>time(),'feemoney'=>$fee,'tradeMoney'=>$request->input('money')*100-$fee]);
                           if($count){
                               $weeksuf = computeWeek(time(),false);
                               $bill = new Billflow();
                               $bill->setTable('agent_billflow_'.$weeksuf);
                               $res = $bill->insert(['agent_id'=>$id,'order_sn'=>$order_sn,'score'=>(int)$request->input('money')*100,'tradeMoney'=>(int)$request->input('money')*100-$fee,'status'=>3,'remark'=>'代理商提现扣除','creatime'=>time()]);
                               if($res){
                                   DB::commit();
                                   $this->unlock($id);
                                   return ['msg'=>'申请成功！请您耐心等待','status'=>1];
                               }else{
                                   DB::rollBack();
                                   $this->unlock($id);
                                   return ['msg'=>'申请失败！请重新填写信息','status'=>0];
                               }
                           }else{
                               DB::rollBack();
                               $this->unlock($id);
                               return ['msg'=>'申请失败！请重新填写信息','status'=>0];
                           }
                       }else{
                           DB::rollBack();
                           $this->unlock($id);
                           return ['msg'=>'发生异常！请联系管理员！','status'=>0];
                       }
                    }
                }catch (Exception $e){
                    DB::rollBack();
                    $this->unlock($id);
                    return ['msg'=>'发生异常！事物进行回滚！','status'=>0];
                }
            }else{
                return ['msg'=>'请忽频繁提交数据！','status'=>0];
            }
        }
    }
}
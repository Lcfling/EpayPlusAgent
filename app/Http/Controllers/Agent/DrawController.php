<?php


namespace App\Http\Controllers\Agent;


use App\Http\Requests\StoreRequest;
use App\Models\Agcount;
use App\Models\Bank;
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
        $data = Draw::where($map)->paginate(5)->appends($request->all());
        foreach ($data as $key =>$value){
            $data[$key]['money']=$data[$key]['money']/100;
            $data[$key]['creatime'] =date("Y-m-d H:i:s",$value["creatime"]);
            $data[$key]['endtime'] = date("Y-m-d H:i:s",$value["endtime"]);
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
        return view('draw.edit',['info'=>$info,'id'=>$id,'banklist'=>$data,'balance'=>$ageCount]);
    }
    /**
     * 保存数据
     */
    public function store(StoreRequest $request){
        $order_sn = time().mt_rand(100000, 999999);
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
        if($request->input('money')*100>$agCount['balance']){
            return ['msg'=>'您输入的金额大于余额！请重新输入','status'=>0];
        }else if(md5(md5($request->input('paypassword')))!=$userInfo['pay_pass']){
            return ['msg'=>'提现密码不正确！','status'=>0];
        }else{
            $lock = $this->lock($id);
            if($lock==true){
                //开启事物
                DB::beginTransaction();
                try{
                    Agcount::where('agent_id',$id)->decrement('balance',(int)$request->input('money')*100);
                    $count = Draw::insert(['agent_id'=>$id,'order_sn'=>$order_sn,'name'=>$bankInfo['name'],'deposit_name'=>$bankInfo['deposit_name'],'deposit_card'=>$bankInfo['deposit_card'],'money'=>$request->input('money')*100,'creatime'=>time()]);
                    if($count){
                        DB::commit();
                        $this->unlock($id);
                        return ['msg'=>'申请成功！请您耐心等待','status'=>1];
                    }else{
                        DB::rollBack();
                        $this->unlock($id);
                        return ['msg'=>'申请失败！请重新填写信息','status'=>0];
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
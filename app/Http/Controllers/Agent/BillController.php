<?php


namespace App\Http\Controllers\Agent;


use App\Models\AgentFee;
use App\Models\Billflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends BaseController
{
    /**
     * 数据列表
     */
    public function index(){
        //获取当前的认证用户
        $agentId = Auth::id();
        $data = AgentFee::where('agent1_id','=',$agentId)->orWhere('agent2_id','=',$agentId)->leftJoin('business','business.business_code','=','agent_fee.business_code')->select('agent_fee.*','business.nickname','business.fee')->get()->toArray();
        foreach ($data as $key =>&$value){
            if($data[$key]['agent1_id']!=$agentId){
                $data[$key]['agent1_id']=$data[$key]['agent2_id'];
                $data[$key]['agent1_fee']=$data[$key]['agent2_fee'];
            }
            $bus = $this->succRate($data[$key]['business_code']);
            //获取总订单
            $allNum = $bus[0]['fq_num'];
            //成功订单
            $succNum = $bus[0]['done_num'];
            //成功订单金额
            $data[$key]['money']=$bus[0]['tol_sore'];
            if($data[$key]['money']==0){
                $data[$key]['money']=0;
            }else{
                $data[$key]['money']=$data[$key]['money']/100;
            }
            //成功率
            if($succNum==0 || $allNum==0){
                $count = 0;
            }else if($succNum!=0 && $allNum!=0){
                $count = number_format($succNum / $allNum,2) * 100;
            }
            $data[$key]['succRate']= $count;
        }
        return view('bill.list',['data'=>$data]);
    }
    /**
     * 获取商户信息
     */
    public function succRate($business_code){
        $business = DB::table('business_count')->where('business_code','=',$business_code)->get()->map(function ($value){
            return (array)$value;
        });
        return $business;
    }
    /**
     * 根据当前代理商和商户id来获取代理商从商户那获取的利润
     */
    public function profit($business_code){
        //获取代理商的id
        $agent = Auth::id();

        $proMoney = Billflow::where('agent_id','=',$agent)->where('business_code','=',$business_code)->where('status','=',2)->sum('score');
        if($proMoney==0){
            $proMoney=0;
        }else{
            $proMoney=$proMoney/100;
        }
        return $proMoney;
    }
    /**
     * 调用存储过程重新生成视图
     */
    /*public function process(){
       DB::statement('call agent_billflow');
    }*/
}
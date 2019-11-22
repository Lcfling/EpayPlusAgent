<?php


namespace App\Http\Controllers\Agent;


use App\Models\Billflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillflowController extends BaseController
{
    /**
     * 数据列表
     */
    public function index(Request $request){
        $agentId = Auth::id();
        if(true==$request->has('creatime')){
            $date=$request->input('creatime');
            $time = strtotime($request->input('creatime'));
            $weeksuf = computeWeek($time,false);
        }else{
            $date="本周";
            $weeksuf = computeWeek(time(),false);
        }
        $bill = new Billflow();
        $bill->setTable('agent_billflow_'.$weeksuf);
        $sql = $bill->where('agent_id','=',$agentId);
        if(true==$request->has('creatime')){
            $start=strtotime($request->input('creatime'));
            $end = strtotime('+1day',$start);
            $sql->whereBetween('creatime',[$start,$end]);
        }
        if(true==$request->input('export')&&true==$request->has('export')){
            $head = array('订单号','提现金额(￥)','实际到账金额(￥)','代理商号','状态','备注','创建时间');
            $data = $sql->select('order_sn','score','tradeMoney','status','remark','creatime')->get()->toArray();
            if (!$data){
                echo "<script>alert('暂无数据，无法导出!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }else{
                foreach ($data as $key => $value){
                    $data[$key]['creatime']=date("Y-m_d H:i:s",$value['creatime']);
                    if($data[$key]['status']==1){
                        $data[$key]['status']=" 支付";
                    }else if($data[$key]['status']==2){
                        $data[$key]['status']="利润";
                    }else{
                        $data[$key]['stuatus']="未知";
                    }
                    /*if($data[$key]['paycode']==1){
                        $data[$key]['paycode']="微信";
                    }else if($data[$key]['paycode']==2){
                        $data[$key]['paycode']="支付宝";
                    }else{
                        $data[$key]['paycode']="未知";
                    }*/
                }
                exportExcel($head,$data,$date.'账户流水','',true);
            }
        }
        $data =$sql->paginate(10)->appends($request->all());
        foreach ($data as $key =>$value){
            $data[$key]['creatime'] =date("Y-m-d H:i:s",$value["creatime"]);
        }
        return view('billflow.list',['list'=>$data,'input'=>$request->all()]);
    }
}
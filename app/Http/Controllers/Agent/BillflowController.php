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
            $time = strtotime($request->input('creatime'));
            $weeksuf = computeWeek($time,false);
        }else{
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
        $data =$sql->paginate(10)->appends($request->all());
        foreach ($data as $key =>$value){
            $data[$key]['creatime'] =date("Y-m-d H:i:s",$value["creatime"]);
        }
        return view('billflow.list',['list'=>$data,'input'=>$request->all()]);
    }
}
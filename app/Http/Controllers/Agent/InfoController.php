<?php


namespace App\Http\Controllers\Agent;


use App\Http\Requests\StoreRequest;
use App\Models\Agent;
use App\Models\AgentFee;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class InfoController extends BaseController
{
    public function index(){
        $id = Auth::id();
        $info =$id?User::find($id)->first():[];
        $data = AgentFee::where('agent1_id','=',$id)->orWhere('agent2_id','=',$id)->leftJoin('business','business.business_code','=','agent_fee.business_code')->select('agent_fee.*','business.nickname','business.fee')->get()->toArray();
        foreach ($data as $key =>$value){
            if($data[$key]['agent1_id']!=$id){
                $data[$key]['agent1_id']=$data[$key]['agent2_id'];
                $data[$key]['agent1_fee']=$data[$key]['agent2_fee'];
            }
        }
        return view('info.list',['list'=>$info,'data'=>$data]);
    }

    /**
     * 个人信息
     */
    public function userinfo(){

        $id = Auth::id();
        $info = $id?User::find($id):[];
        return view('info.userinfo',['userinfo'=>$info]);
    }
    /**
     * 修改个人资料
     */
    public function updateInfo(StoreRequest $request){
        $id = Auth::id();
        $count = User::where('id',$id)->update(['agent_name'=>HttpFilter($request->input('agent_name')),'updatetime'=>time()]);
        if($count){
            return ['msg'=>'修改成功！','status'=>1];
        }else{
            return ['msg'=>'修改失败！','status'=>0];
        }
    }
    /**
     * 效验旧密码
     */
    public function valPwd(StoreRequest $request){
        //用户输入密码
        $pwd = $request->input('oldpwd');
        $id = Auth::id();
        $userInfo = $id?User::find($id):[];
        if(!App::make('hash')->check(HttpFilter($pwd),$userInfo['password'])){
            return ['msg'=>'旧密码不正确！','status'=>1];
        }
    }
    /**
     * 修改密码
     */
    public function resPwd(StoreRequest $request){
        //用户输入的旧密码
        $oldpwd = $request->input('oldpwd');
        //用户输入的新密码
        $pwd = $request->input('pwd');
        $id = Auth::id();
        $userInfo = $id?User::find($id):[];
        if(!App::make('hash')->check(HttpFilter($oldpwd),$userInfo['password'])){
            return ['msg'=>'旧密码不正确','status'=>0];
        }else{
            $count = User::where('id',$id)->update(['password'=>bcrypt(HttpFilter($pwd)),'updatetime'=>time()]);
            if ($count){
                return ['msg'=>'修改成功！','status'=>1];
            }else{
                return ['msg'=>'修改失败！','status'=>0];
            }
        }
    }
    /**
     * 效验原支付密码
     */
    public function valPaypwd(StoreRequest $request){
        //旧密码
        $oldpaypwd = $request->input('oldpaypwd');
        $id = Auth::id();
        $userInfo = $id?User::find($id):[];
        if(md5(md5(HttpFilter($oldpaypwd)))!=$userInfo['pay_pass']){
            return ['msg'=>'旧密码错误！','status'=>1];
        }
    }
    /**
     * 修改支付密码
     */
    public function resPaypwd(StoreRequest $request){
        //旧密码
        $oldpaypwd = $request->input('oldpaypwd');
        //新密码
        $paypwd = $request->input('paypwd');
        $id = Auth::id();
        $userInfo = $id?User::find($id):[];
        if(md5(md5(HttpFilter($oldpaypwd)))!=HttpFilter($userInfo['pay_pass'])){
            return ['msg'=>'旧密码错误！'];
        }else{
            $count = User::where('id',$id)->update(['pay_pass'=>md5(md5(HttpFilter($paypwd))),'updatetime'=>time()]);
            if($count){
                return ['msg'=>'修改成功！','status'=>1];
            }else{
                return ['msg'=>'修改失败！','status'=>0];
            }
        }
    }
    /**
     * 设置支付密码
     */
    public function setPayPwd(StoreRequest $request){
        //获取支付密码
        $paypassword = $request->input('paypassword');
        //获取认证用户
        $agent = Auth::id();
        $userInfo = $agent?User::find($agent):[];
        if($userInfo['pay_pass']!=null||$userInfo['pay_pass'] != ''){
            return ['msg'=>'错误！','status'=>0];
        }else{
            $count = User::where('id','=',$agent)->update(['pay_pass'=>md5(md5(HttpFilter($paypassword)))]);
            if($count){
                return ['msg'=>'设置成功！','status'=>1];
            }else{
                return ['msg'=>'设置失败！','status'=>0];
            }
        }
    }
}
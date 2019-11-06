<?php


namespace App\Http\Controllers\Agent;


use App\Http\Requests\StoreRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function index(){
        $id = Auth::id();
        $info =$id?User::find($id)->first():[];
        return view('info.list',['list'=>$info]);
    }

    /**
     * 个人信息
     */
    public function userinfo(){

        $id = Auth::id();
        $info = $id?User::find($id):[];
        //die('ssss');
        return view('info.userinfo',['userinfo'=>$info]);
    }
    /**
     * 修改个人资料
     */
    public function updateInfo(StoreRequest $request){
        $id = Auth::id();
        $count = User::where('id',$id)->update(['agent_name'=>$request->input('agent_name'),'updatetime'=>time()]);
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
        if(!App::make('hash')->check($pwd,$userInfo['password'])){
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
        if(!App::make('hash')->check($oldpwd,$userInfo['password'])){
            return ['msg'=>'旧密码不正确','status'=>0];
        }else{
            $count = User::where('id',$id)->update(['password'=>bcrypt($pwd),'updatetime'=>time()]);
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
        if(md5(md5($oldpaypwd))!=$userInfo['pay_pass']){
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
        if(md5(md5($oldpaypwd))!=$userInfo['pay_pass']){
            return ['msg'=>'旧密码错误！'];
        }else{
            //$count = DB::table('business')->where('business_code',$id)->update(['pay_pass'=>md5(md5($paypwd)),'updatetime'=>time()]);
            $count = User::where('id',$id)->update(['pay_pass'=>md5(md5($paypwd)),'updatetime'=>time()]);
            if($count){
                return ['msg'=>'修改成功！','status'=>1];
            }else{
                return ['msg'=>'修改失败！','status'=>0];
            }
        }
    }
}
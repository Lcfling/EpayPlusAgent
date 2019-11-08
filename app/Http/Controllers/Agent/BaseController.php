<?php
/**
 * 基础控制器，目前只加入一个公共方法，可以拓展
 *
 * @author      fzs
 * @Time: 2017/07/14 15:57
 * @version     1.0 版本号
 */
namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use PragmaRX\Google2FA\Google2FA;

class BaseController extends Controller
{
    /**
     * 返回自定义标准json格式
     *
     * @access protected
     * @param string $lang 语言包
     * @param number $res 结果code
     * @return json
     */
    protected function resultJson($lang,$res)
    {
        return strstr($lang,'fzs')?['status'=>$res,'msg'=>trans($lang)]:['status'=>$res,'msg'=>$lang];
    }

    public function lock($functions){
        $code=time().rand(100000,999999);
        //随机锁入队
        Redis::rPush("lock_".$functions,$code);
        //随机锁出队
        $codes=Redis::LINDEX("lock_".$functions,0);
        if ($code != $codes){
            return false;
        }else{
            return true;
        }
    }

    public function unlock($functions){
        Redis::del("lock_".$functions);
    }
    public function verifyGooglex($code,$account,$modle){
        $userInfo=$modle->getUserInfo($account);
        $secret=$code;
        $google2fa = new Google2FA();
        if($google2fa->verifyKey($userInfo["ggkey"], $secret)){
            return true;
        }else{
            return false;
        }
    }
}

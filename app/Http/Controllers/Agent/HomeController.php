<?php
/**
 * 用户登陆过后首页以及一些公共方法
 *
 * @author      fzs
 * @Time: 2017/07/14 15:57
 * @version     1.0 版本号
 */
namespace App\Http\Controllers\Agent;
use App\Models\Admin;
use App\Models\Agcount;
use App\Models\Billflow;
use App\Models\User;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
//use App\Http\Controllers\Controller;
class HomeController extends BaseController
{
    /**
     * 后台首页
     */
    public function index() {

        $menu = new Admin();

        return view('agent.index',['menus'=>$menu->Menus(),'mid'=>$menu->getMenuId(),'parent_id'=>$menu->getParentMenuId()]);
    }
    /**
     * 验证码
     */
    public function verify(){
        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(255, 255, 255);
        $builder->build(130,40);
        $phrase = $builder->getPhrase();
        Session::flash('code', $phrase); //存储验证码
        return response($builder->output())->header('Content-type','image/jpeg');
    }


    /**
     * 欢迎首页
     */
    public function welcome(){
        //获取当前代理商的余额
        $agent = Auth::id();
        $balance = Agcount::where('agent_id','=',$agent)->select('balance')->first();
        //获取今日的收益
        $time = time();
        $weeksuf = computeWeek($time,false);
        $bill = new Billflow();
        $bill->setTable('agent_billflow_'.$weeksuf);
        $money = $bill->where('agent_id','=',$agent)->whereBetween('creatime',[$time,strtotime('+1day',$time)])->sum('score');
        if($money==0){
            $money=0;
        }else{
            $money=$money/100;
        }
        return view('admin.welcome',['sysinfo'=>$this->getSysInfo(),'balance'=>$balance,'money'=>$money]);
    }
    /**
     * 排序
     */
    public function changeSort(Request $request){
        $data = $request->all();
        if(is_numeric($data['id'])){
            $res = DB::table('admin_'.$data['name'])->where('id',$data['id'])->update(['order'=>$data['val']]);
            if($res)return $this->resultJson('fzs.common.success', 1);
            else return $this->resultJson('fzs.common.fail', 0);
        }else{
            return $this->resultJson('fzs.common.wrong', 0);
        }
    }
    /**
     * 获取系统信息
     */
    protected function getSysInfo(){
        $sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['phpv']           = phpversion();
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['time']           = date("Y-m-d H:i:s");
        $sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
        $mysqlinfo = DB::select("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysqlinfo[0]->version;
        return $sys_info;
    }
}

<?php
/**
 * 用户登陆
 *
 * @author      fzs
 * @Time: 2017/07/14 15:57
 * @version     1.0 版本号
 */
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Agent\BaseController;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers {authenticated as oriAuthenticated;}
    use AuthenticatesUsers {login as doLogin;}

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/agent/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        /*$user = new User();
        if(!$this->verifyGooglex($request->input('ggkey'),HttpFilter($request->input('account')),$user)){
            return redirect('/agent/login')->withErrors([trans('fzs.login.false_verify')]);
        }*/
        if($request->input('verity')==session('code'))return $this->doLogin($request);
        else return redirect('/agent/login')->withErrors([trans('fzs.login.false_verify')]);
    }
    public function username()
    {
        return 'account';
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/agent/');
    }

    protected function authenticated(Request $request, $user)
    {
        Log::addLogs(trans('fzs.login.login_info'),'/agent/login',$user->id);
        return $this->oriAuthenticated($request, $user);
    }

}

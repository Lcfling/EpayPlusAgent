<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}

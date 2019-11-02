<?php

namespace App\Http\Controllers\Ucenter;

use App\Models\Square;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;
class SquareController extends Controller
{
    //

    public function publish(Request $request){

        $input = $request->input();
        $model=new Square();
        for ($i=0;$i<10000000;$i++){
            $value=array('user_id'=>1,'content'=>$i,'type'=>1);
            $res=$model->insertdate($value);
        }

        print_r($res);
    }
    public function index(Request $request){
        $model=new Square();
        $model->getFriendsSquare($request->get("content"));
    }
    public function test(Request $request){
        //die("ssss");
        $google2fa = new Google2FA();

        /*$secretKey=$google2fa->generateSecretKey();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            "天马",
            "aylui2009@163.com",
            $secretKey
        );*/
        echo $qrCodeUrl;
    }
    public function test2(Request $request){
        //die("ssss");
        $google2fa = new Google2FA();

        $secretKey="E252WPUQVPDK5W5U";
        $secret="616796";
        $st=$google2fa->verifyKey($secretKey, $secret);
        print_r($st);
        echo "ssss";
    }
}

<?php

namespace Addons\Ubind\Controller;
use Home\Controller\AddonsController;

class UbindController extends AddonsController{
    //登录页面
    public function index(){  
        //$opeind=$_GET['openid'];
        $this->assign('openid',get_openid());
        $this->display();
    }

    //登录页面
    public function unbind(){ 
        if(isset($_POST['sure'])){
            $user=M('user');
            $openid=$_POST['openid'];
            $openid=get_openid();
            $a=$user->where("openid=".'"'.$openid.'"')->setField('studentid',0);
            if($a){
                $this->success("解绑成功,等待跳转后就可绑定其它账号",U('/addon/Binding/Binding/login/'),2);
            }
        }
    }
}

<?php

namespace Addons\BxqScore\Controller;
use Home\Controller\AddonsController;

class BxqScoreController extends AddonsController{
    //登录vpn获取验证码并且保存cookie设置标记
    public function verify(){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($rs,CURLOPT_REFERER,"http://vpn.hpu.edu.cn/");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
        $cookie = trim($str[1]); //获得COOKIE（SESSIONID）
        //$arr=explode("=", $cookie);
        //print_r($arr);
        //setcookie($arr[0],$arr[1]);
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        curl_close($ch);
        //echo $content."<br>";
        /*
            登陆并设置新的TWFID和ENABLE_RANDCODE获取重定向地址
        */
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        $result = end($redis->srandmember('set',1));
        $res = explode(':',$result);
        $uid = $res[0];
        $pass = $res[1];
        //$Model = M();
        //$rand=rand(1000,9000);
        //$data=$Model->query("SELECT studentid,IdCard FROM wp_user WHERE('studentid'>'311300000000' and 'IdCard'!='') ORDER BY RAND() LIMIT 1");
        $ch=curl_init();
        //$post="mitm_result=&svpn_name=".$data[0]['studentid']."&svpn_password=".substr($data[0]['IdCard'],12)."&svpn_rand_code=";
        $post="mitm_result=&svpn_name=".$uid."&svpn_password=".$pass."&svpn_rand_code=";

        /*$arr=$this->gett();
        foreach($arr as $k=>$v){
            $rand0=$k;
            $rand1=substr($v,12);
        }

        $ch=curl_init();
        $post="mitm_result=&svpn_name=".$rand0."&svpn_password=".$rand1."&svpn_rand_code="; */
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //带上上登陆前的cookie
        curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str1); //正则匹配
        $cookie2=trim($str1[1][0]);
        $cookie3=trim($str1[1][1]);
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie2;$cookie3");
        $arr3=explode("=", $cookie3);
        $arr2=explode("=", $cookie2);
        curl_close($ch);
        //登录教务处
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //curl_setopt($ch,CURLOPT_COOKIEFILE, $cookieFile);
        //使用vpn登陆后的cookie
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3"); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配教务处登陆时设置的cookie
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
        $cookie4 = trim($str[1]); //获得COOKIE（SESSIONID）
        $arr4=explode("=", $cookie4);
        //global $arr4;
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie4);
        //setcookie($arr4[0],$arr4[1]);
        curl_close($ch);

        //获取验证码
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/validateCodeAction.do";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4"); 
        setcookie("isl","1");
        setcookie($arr2[0],$arr2[1]);
        setcookie($arr3[0],$arr3[1]);
        setcookie($arr4[0],$arr4[1]);
        //print_r($_COOKIE);
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie2); 
        //curl_setopt($ch,CURLOPT_COOKIEFILE,$cookieFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);
        echo $content;

        /*$xmlstr=$content;
        $openid=get_openid();
        if($openid=='-1'){
            $img_id='default'; 
        }else{
            $img_id=$openid;
        }
        $filename=$img_id.".jpg";//要生成的图片名字
        //$xmlstr = file_get_contents('https://niool.com/weixin/index.php?s=/addon/JiDian/JiDian/verify');
        //echo $xmlstr;
        //echo $this->StrToBin($xmlstr);
        $jpg = $xmlstr;//得到post过来的二进制原始数据
        $file = fopen("Verify/".$filename,"w");//打开文件准备写入
        fwrite($file,$jpg);//写入
        fclose($file);//关闭*/

    }


    //登录页面
    public function login(){
        $token = 'S6Kv7GKT0JBea_sP-bA0qLMz3k0RD8b-rSix8PCaaEYtZrZE2bFHELDWuMxCIpnW3dENX_2Vs3_FtQzBXv5jGtKYF2AhGPdIDwINUzU7OLzeBUUAzRPkl2yIonQP3UavNOYhAGAUVF';
        $jsapi = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi");
        $jsapi = json_decode($jsapi);
        $j = get_object_vars($jsapi);
        $jsapi = $j['ticket'];//get JSAPI
        $timestamp = time();
        $noncestr = $timestamp;
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $and = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url."";
        $signature = sha1($and);

        $user=M('user');
        $openid=get_openid();
        $mm = $user->where("openid=".'"'.$openid.'"')->getField('password');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $this->assign('mm',$mm);

        $this->assign('timestamp',$timestamp);
        $this->assign('noncestr',$noncestr);
        $this->assign('signature',$signature);

        $this->assign('studentid',$studentid);
        $this->display();
    }

    //本学期成绩
    public function bxqcj(){
        if(isset($_COOKIE['isl'])){
            $cookie4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $cookie2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $cookie3="TWFID"."=".$_COOKIE['TWFID'];
        }
        if(isset($_POST['submit'])){
            $openid=$_POST['openid'];
            $zjh=$_POST['zjh'];
            $mm=$_POST['mm'];
            $v_yzm=$_POST['v_yzm'];
        }

        /*$ch=curl_init();
        $openid=get_openid();
        if($openid=='-1'){
            $img_id='default';
        }else{
            $img_id=$openid;
        }
        $post="url=http://niool.com/weixin/Verify/".$img_id.".jpg&service=OcrKingForCaptcha&language=eng&charset=7&apiKey=7a035b90a8142c343eq9CThVaZK2nbB9kYb1LrOeCxqtH0wl7upz8Hk8pii90sXv6e1kd6qHQ9&type=http://niool.com/weixin/Verify/".$img_id.".jpg";
        curl_setopt($ch,CURLOPT_URL,"http://lab.ocrking.com/ok.html");
        //curl_setopt($ch,CURLOPT_REFERER,"http://lab.ocrking.com/");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //带上上登陆前的cookie
        //curl_setopt($ch,CURLOPT_COOKIE,"$cookie_1;$cookie_2");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content1=curl_exec($ch);
        curl_close ($ch);
        preg_match("/<result>(.*?)<\/result>/si", $content1,$str1);
        $verify=$str1[1];*/

        $params = array (
            'zjh' => $zjh,
            'mm' => $mm,
            'v_yzm' => $v_yzm
            );


        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie1);
        //curl_setopt($ch,CURLOPT_COOKIE,$cookie2);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        curl_close ( $ch );


        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/bxqcjcxAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);

        $url="https://vpn.hpu.edu.cn/por/logout.csp?rnd=9161307384583139";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie2;$cookie3;$cookie4");
        setcookie("isl","0");
        setcookie("TWFID","deleted");
        setcookie("expires","Saturday, 17-Jan-17 13:41:29 GMT");
        setcookie("path","/");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_exec($ch);
        curl_close($ch);


        $content = iconv("gbk", "utf-8", $content);
        //echo $content;
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[5];
        $arr=$this->get_td_array($table);//执行函数
        $data=array();
        foreach($arr as $k=>$v){
            if(count($v)>2){
                $data['kcm'][]=$v[2];
                $data['xf'][]=$v[4];
                $data['kcsx'][]=$v[5];
                $data['zgf'][]=$v[6];
                $data['zdf'][]=$v[7];
                $data['pjf'][]=$v[8];
                $data['cj'][]=$v[9];
                $data['mc'][]=$v[10];
            }
        }
        $con=count($data['kcm']);
        //print_r($arr)."<br>";
        $this->assign('data',$data);
        $this->assign('con',$con);
        $this->display();
    }

    //正则匹配表格
    public function get_td_array($table) { 
        $table = preg_replace("'<table[^>]*?>'si","",$table); 
        $table = preg_replace("'<tr[^>]*?>'si","",$table); 
        $table = preg_replace("'<td[^>]*?>'si","",$table); 
        $table = str_replace("</tr>","{tr}",$table); 
        //PHP开源代码
        $table = str_replace("</td>","{td}",$table); 
        //去掉 HTML 标记  
        $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table); 
        //去掉空白字符   
        $table = preg_replace("'([rn])[s]+'","",$table); 
        $table = str_replace(" ","",$table); 
        $table = str_replace("&nbsp;","",$table); 
        $table = str_replace(" ","",$table);
        $table = explode('{tr}', $table); 
        array_pop($table);  
        foreach ($table as $key=>$tr) { 
            $td = explode('{td}', $tr); 
            array_pop($td); 
            $td_array[] = $td; 
        } 
        return $td_array; 
    }

    public function share(){
        $appid = 'wxc5d2229adc0102a3';
        $secret = '886d44b2cfec214e8d752b731e5da432 ';
        echo get_token();
        die;
        /*$auth = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&grant_type=authorization_code");//通过code换取网页授权access_token
        $jsonauth = json_decode($auth); //对JSON格式的字符串进行编码
        $arrayauth = get_object_vars($jsonauth);//转换成数组
        $openid = $arrayauth['openid'];//输出openid
        $access_token = $arrayauth['access_token'];
        $_SESSION['openid'] = $openid;
        
        $accesstoken = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."");//获取access_token
        $token = json_decode($accesstoken); //对JSON格式的字符串进行编码
        $t = get_object_vars($token);//转换成数组*/
        $access_token = 'fsmU40Bgan35nKkHo0NjBZPoaQ6ywUA1RXHrWxO2iP34cQoHbc8yPb_4yhsATmiOOM-_aHyAPtrXSPBtEHwW-qfL8yNGg6p95AarVlpemp_tXp3eNege6k8zLGlkloZhMZGhAJAZMT';
        
        $jsapi = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi");
        $jsapi = json_decode($jsapi);
        $j = get_object_vars($jsapi);
        $jsapi = $j['ticket'];//get JSAPI

        $time = time();
        $noncestr= $time;
        $jsapi_ticket= $jsapi;
        $timestamp='1474446849';
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $and = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url."";
        $signature = sha1($and);
        return $signature;
    }
   
}

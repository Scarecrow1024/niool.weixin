<?php

namespace Addons\Binding\Controller;
use Home\Controller\AddonsController;

class BindingController extends AddonsController{
    public function rinit(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1',8888);
        //$result = $redis->srandmember('set',1);
        //var_dump($result);die;
        $data = ['311502020328:202570','311505000609:196443','311623010301:083821','311510060121:142529','311504000825:240715','311503050201:221263','311506000407:05932X','311614010120:110206','311606001502:126523','311610010411:275022','311502020101:077967','311605040128:183718','311502020102:109806','311510030210:200527','311609000514:015816','311609030117:24003X','311513020216:142632','311503050205:032521','311611000124:151813','311604000712:054316','311622000408:040567','311506000529:241519','311611000419:022674','311603000508:284523','311621020321:196411','311707090132:233030','311603000506:065547','311708030202:187724','311508070301:125727','311608070415:020513','311608070416:30201X','311608070417:314516','311608070418:090012','311608070419:061111'];
        foreach($data as $v){
            $res = $redis->sadd('set',$v);
            var_dump($res);
        }
        
    }

    //登录vpn获取验证码并且保存cookie设置标记
    public function verify(){
        set_time_limit(0);
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($rs,CURLOPT_REFERER,"http://vpn.hpu.edu.cn/");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
        $session = trim($str[1]); //获得COOKIE（SESSIONID）
        curl_close($ch);
        //echo $content."<br>";
        /*
            登陆并设置新的TWFID和ENABLE_RANDCODE获取重定向地址
              
        */
        $redis = new \Redis();
        $redis->connect('127.0.0.1',8888);
        $result = end($redis->srandmember('set',1));
        $res = explode(':',$result);
        $uid = $res[0];
        $pass = $res[1];

        /*$info = $this->get_info();
        $stud = $info[0]['studentid'];
        $pass = substr($info[0]['IdCard'],12);*/

        $ch=curl_init();
        $post="mitm_result=&svpn_name=".$uid."&svpn_password=".$pass."&svpn_rand_code=";
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //带上上登陆前的cookie
        curl_setopt($ch,CURLOPT_COOKIE,$session);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str1); //正则匹配
        $session2=trim($str1[1][0]);
        $session3=trim($str1[1][1]);
        curl_setopt($ch, CURLOPT_COOKIE, "$session2;$session3");
        $arr3=explode("=", $session3);
        $arr2=explode("=", $session2);
        curl_close($ch);
        //登录教务处
        $ch=curl_init();
        set_time_limit(0);
        $url="https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //curl_setopt($ch,CURLOPT_COOKIEFILE, $sessionFile);
        //使用vpn登陆后的cookie
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配教务处登陆时设置的cookie
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
        $session4 = trim($str[1]); //获得COOKIE（SESSIONID）
        $arr4=explode("=", $session4);
        //global $arr4;
        //curl_setopt($ch, CURLOPT_COOKIE, $session4);
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
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        setcookie("isl","1");
        setcookie($arr2[0],$arr2[1]);
        setcookie($arr3[0],$arr3[1]);
        setcookie($arr4[0],$arr4[1]);
        //print_r($_COOKIE);
        //curl_setopt($ch,CURLOPT_COOKIE,$session2);
        //curl_setopt($ch,CURLOPT_COOKIEFILE,$sessionFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);
        echo $content;

    }
    public function verify1(){
        session_start();
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
        $session = trim($str[1]); //获得COOKIE（SESSIONID）
        curl_close($ch);

        $info = $this->get_info();
        $stud = $info[0]['studentid'];
        $pass = substr($info[0]['IdCard'],12);

        $ch=curl_init();
        $post="mitm_result=&svpn_name=".$stud."&svpn_password=".$pass."&svpn_rand_code=";
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //带上上登陆前的cookie
        curl_setopt($ch,CURLOPT_COOKIE,"$session");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str1); //正则匹配  
        $session2=trim($str1[1][0]);
        $session3=trim($str1[1][1]);
        curl_setopt($ch, CURLOPT_COOKIE, "$session2;$session3");
        curl_close($ch);
        //登录教务处
        $ch=curl_init();
        set_time_limit(0);
        $url="https://vpn.hpu.edu.cn/web/1/http/0/218.196.240.97/";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        //curl_setopt($ch,CURLOPT_COOKIEFILE, $sessionFile);
        //使用vpn登陆后的cookie
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3"); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配教务处登陆时设置的cookie
        preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $session4 = trim($str[1]); //获得COOKIE（SESSIONID）
        curl_close($ch);

        //echo $stud."#".$pass."<br>";

        //获取验证码
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/validateCodeAction.do";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4"); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);
        echo $content;

        //退出
        $ch=curl_init();
        $url="https://vpn.hpu.edu.cn/por/logout.csp?rnd=9161307384583139";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4"); 
        setcookie("TWFID","deleted");
        setcookie("expires","Saturday, 17-Jan-17 13:41:29 GMT");
        setcookie("path","/");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $logout=curl_exec($ch);
        curl_close($ch);
        session_destroy();

        /*$data = $this->get_info();
        echo $data[0]['studentid']."<br>";
        echo substr($data[0]['IdCard'],12);*/
    }

    //递归获取账号密码
    public function get_info(){
      static $data = array();
      $user = M("user");
      $map['studentid'] =array('like',array('3114%','3115%'),'OR');
      $map['id'] =array('eq',rand(5000,25000));
      $data = $user->field('id,IdCard,studentid')->where($map)->limit(1)->select();
      if($data==null){
        $data = $this->get_info();
      }
      return $data;
    }

    //登录页面
    public function login(){
        $openid=$_GET['openid'];
        $this->assign('openid',$openid);
        $this->display();
    }

    // 得到学生个人信息
    function getStudentInfo($mm,$session2,$session3,$session4){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xjInfoAction.do?oper=xjxx");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $sessionFile);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $content=curl_exec($ch);
        curl_close ( $ch );
        $content = iconv("gbk", "utf-8", $content);
        return $content;
      
    }

    //本学期成绩
    public function binding(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        if(isset($_POST['submit'])){
            $openid=$_POST['openid'];
            $zjh=$_POST['zjh'];
            $mm=$_POST['mm'];
            $v_yzm=$_POST['v_yzm'];
        }
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
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close ( $ch );

        $content=$this->getStudentInfo();
        //echo $content;
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[5];
        $arr=$this->get_td_array($table);//执行函数
        $data = array(
                "studentid"=>trim($arr[2][2]),
                "password"=>$mm,
                "name"=>trim($arr[2][4]),
                "sex"=>trim($arr[3][9]),
                "major"=>trim($arr[3][19]),
                "classId"=>trim($arr[3][55]),
                "address"=>trim($arr[3][21]),
                "IdCard"=>trim($arr[3][7]),
                "high_school"=>trim($arr[3][29]),
                "high_schoolId"=>trim($arr[3][35]),
                "department"=>trim($arr[3][47]),
                );
        //绑定学生信息
        if(strlen($data['IdCard'])<16){
            $this->error("认证失败，请检查用户名或密码是否正确",U('/addon/Binding/Binding/login/'));
        }else{
            $user=M('user');
            $openid=get_openid();
            $card = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
            if($card!=0){
                //$name = $user->where("openid=".'"'.$openid.'"')->getField('name');
                $this->error("该账号已经绑定,请勿重复绑定,如需重新绑定请联系客服",'',3);
            }else{
                //if(substr($data['studentid'], 0, 4)!='3117'){
                    $redis = new \Redis();
                    $redis->connect('127.0.0.1',8888);
                    $redis->sadd('set',$data['studentid'].':'.substr($data['IdCard'], 12));
                //}
                
                $this->subscribe();
                //保存图文版课表
                $course=$this->getXuanke1();
                //保存网页版课表
                $webcourse=$this->getXuanke2();
                //保存源课表
                $yscore=$this->getXuanke3();
                //保存历年成绩
                $openid=get_openid();
                //$score=$this->getLiNian();
                //$data['score']=$score;
                $data['webcourse']=$webcourse;
                $data['course']=$course;
                $data['yscore']=$yscore;
                $bind=$user->where("openid=".'"'.$openid.'"')->save($data);
                if($bind){
                    //退出
                    $ch=curl_init();
                    $url="https://vpn.hpu.edu.cn/por/logout.csp?rnd=9161307384583139";
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
                    curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
                    curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
                    curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
                    setcookie("isl","0");
                    setcookie("TWFID","deleted");
                    setcookie("expires","Saturday, 16-Jan-16 13:41:29 GMT");
                    setcookie("path","/");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                    $logout=curl_exec($ch);
                    curl_close($ch);
                    $this->success($data["name"]."同学绑定成功",U('/addon/CourseList/CourseList/course/'));
                }
            }
        }
        //绑定课程表
        
    }

    // 关注公众号事件
    public function subscribe() {
        $user=D('user');
        $openid=get_openid();
        $data=array();
        $data['openid']=$openid;
        $data['subscribe_time']=date("Y-m-d H:i",time());
        $is=$user->where("openid=".'"'.$openid.'"')->find();
        if($is==NULL){
            $user->add($data);
        }
        return true;
    }

    function getLnKb1(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $ch=curl_init();
        $post="zxjxjhh=2017-2018-1-1";
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $content=iconv("gbk", "utf-8", $content);
        $html=new SimpleHtmlController();
        $content=str_replace("一","1",$content);
        $content=str_replace("二","2",$content);
        $content=str_replace("三","3",$content);
        $content=str_replace("四","4",$content);
        $content=str_replace("五","5",$content);
        $content=str_replace("六","6",$content);
        $content=str_replace("七","7",$content);
        $content=str_replace("八","8",$content);
        $content=str_replace("九","9",$content);
        $html->load($content);
        $table=$html->find('table')[9];
        $arr=$this->get_td_array($table);//执行函数
        //第一步获取到所有的课程一个课程放到一个数组中去
        $data = array();
        for($i=1;$i<count($arr);$i++){
            if((count($arr[$i])%17)==0){
                if(count($arr[$i])>17){
                    $data[$i]=array_chunk($arr[$i], 17);
                }else{
                    $data[$i][]=$arr[$i];
                    if(count($arr[$i+1])==7){
                        $data[$i][]=$arr[$i+1];
                    }
                    if(count($arr[$i+1])==7&&count($arr[$i+2])==7){
                        $data[$i][]=$arr[$i+2];
                    }
                    if(count($arr[$i+2])==7&&count($arr[$i+3])==7){
                        $data[$i][]=$arr[$i+3];
                    }
                }   
            }
        }
        //return $data;
        $js_data=array();
        foreach($data as $k=>$v){
            for($i=0;$i<count($v);$i++){
                if(count($data[$k][$i])==17){
                    $js_data[trim($data[$k][$i][12])][]="第".trim($data[$k][$i][13])."节有课:\n".trim($data[$k][$i][2])."\n".trim($data[$k][$i][16].$data[$k][$i][17])."\n".trim($data[$k][$i][7]).trim($data[$k][$i][11]);
                }else{
                    $js_data[trim($data[$k][$i][1])][]="第".trim($data[$k][$i][2])."节有课:\n".trim($data[$k][0][2])."\n".trim($data[$k][$i][5].$data[$k][$i][6])."\n".trim($data[$k][0][7]).trim($data[$k][$i][0]);
                }
            }
        }

        return json_encode($js_data);
    }

    function getLnKb2(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $ch=curl_init();
        $post="zxjxjhh=2017-2018-1-1";
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ($ch);
        curl_close ($ch);
        $content=iconv("gbk", "utf-8", $content);

        $html=new SimpleHtmlController();

        $content=str_replace("一","1",$content);
        $content=str_replace("二","2",$content);
        $content=str_replace("三","3",$content);
        $content=str_replace("四","4",$content);
        $content=str_replace("五","5",$content);
        $content=str_replace("六","6",$content);
        $content=str_replace("七","7",$content);
        $content=str_replace("八","8",$content);
        $content=str_replace("九","9",$content);

        $html->load($content);
        $table=$html->find('table')[9];
        $arr=$this->get_td_array($table);//执行函数
        //第一步获取到所有的课程一个课程放到一个数组中去
        $data = array();
        for($i=1;$i<count($arr);$i++){
            if((count($arr[$i])%17)==0){
                if(count($arr[$i])>17){
                    $data[$i]=array_chunk($arr[$i], 17);
                }else{
                    $data[$i][]=$arr[$i];
                    if(count($arr[$i+1])==7){
                        $data[$i][]=$arr[$i+1];
                    }
                    if(count($arr[$i+1])==7&&count($arr[$i+2])==7){
                        $data[$i][]=$arr[$i+2];
                    }
                    if(count($arr[$i+2])==7&&count($arr[$i+3])==7){
                        $data[$i][]=$arr[$i+3];
                    }
                }   
            }
        }
        //return $data;
        $js_data=array();
        foreach($data as $k=>$v){
            for($i=0;$i<count($v);$i++){
                if(count($data[$k][$i])==17){
                    $js_data[trim($data[$k][$i][12])][trim($data[$k][$i][13])]['score'][]=trim($data[$k][$i][2]);
                    $js_data[trim($data[$k][$i][12])][trim($data[$k][$i][13])]['teacher'][]=trim($data[$k][$i][7]).trim($data[$k][$i][11]);
                    $js_data[trim($data[$k][$i][12])][trim($data[$k][$i][13])]['add'][]=trim($data[$k][$i][16].$data[$k][$i][17]);
                    $js_data[trim($data[$k][$i][12])][trim($data[$k][$i][13])]['week'][]=trim($data[$k][$i][11]);
                }else{
                    $js_data[trim($data[$k][$i][1])][trim($data[$k][$i][2])]['score'][]=trim($data[$k][0][2]);
                    $js_data[trim($data[$k][$i][1])][trim($data[$k][$i][2])]['teacher'][]=trim($data[$k][0][7]).trim($data[$k][$i][0]);
                    $js_data[trim($data[$k][$i][1])][trim($data[$k][$i][2])]['add'][]=trim($data[$k][$i][5].$data[$k][$i][6]);
                    $js_data[trim($data[$k][$i][1])][trim($data[$k][$i][2])]['week'][]=trim($data[$k][$i][0]);
                }
            }
        }
        $js_data=json_encode($js_data);
        return $js_data;
    }

    function getLnKb3(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $ch=curl_init();
        $post="zxjxjhh=2017-2018-1-1";
        curl_setopt($ch,CURLOPT_URL,"https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do");
        curl_setopt($ch,CURLOPT_REFERER,"https://vpn.hpu.edu.cn/por/login_psw.csp");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $content=iconv("gbk", "utf-8", $content);
        $html=new SimpleHtmlController();
        $html->load($content);
        $content=$html->find('table')[7];
        $content=html_entity_decode($content);
        return $content;
    }


    //图文版课表
    function getXuanke1(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $url="https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xkAction.do?actionType=6";
        //$url = "https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do?zxjxjhh=2015-2016-2-1";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $content=iconv("gbk", "utf-8", $content);
        $html=new SimpleHtmlController();
        $content=str_replace("一","1",$content);
        $content=str_replace("二","2",$content);
        $content=str_replace("三","3",$content);
        $content=str_replace("四","4",$content);
        $content=str_replace("五","5",$content);
        $content=str_replace("六","6",$content);
        $content=str_replace("七","7",$content);
        $content=str_replace("八","8",$content);
        $content=str_replace("九","9",$content);
        $html->load($content);
        $table=$html->find('table')[7];
        $arr=$this->get_td_array($table);//执行函数
        //第一步获取到所有的课程一个课程放到一个数组中去
        $data = array();
        for($i=1;$i<count($arr);$i++){
            if((count($arr[$i])%18)==0){
                if(count($arr[$i])>18){
                    $data[$i]=array_chunk($arr[$i], 18);
                }else{
                    $data[$i][]=$arr[$i];
                    if(count($arr[$i+1])==7){
                        $data[$i][]=$arr[$i+1];
                    }
                    if(count($arr[$i+1])==7&&count($arr[$i+2])==7){
                        $data[$i][]=$arr[$i+2];
                    }
                    if(count($arr[$i+2])==7&&count($arr[$i+3])==7){
                        $data[$i][]=$arr[$i+3];
                    }
                }   
            }
        }
        //return $data;
        $js_data=array();
        foreach($data as $k=>$v){
            for($i=0;$i<count($v);$i++){
                if(count($data[$k][$i])==18){
                    $js_data[$data[$k][$i][12]][]="第".trim($data[$k][$i][13])."节有课:\n".trim($data[$k][$i][2])."\n".trim($data[$k][$i][16].$data[$k][$i][17])."\n".trim($data[$k][$i][7]).trim($data[$k][$i][11]);
                }else{
                    $js_data[$data[$k][$i][1]][]="第".trim($data[$k][$i][2])."节有课:\n".trim($data[$k][0][2])."\n".trim($data[$k][$i][5].$data[$k][$i][6])."\n".trim($data[$k][0][7]).trim($data[$k][$i][0]);
                }
            }
        }

        return json_encode($js_data);

        $con=count($arr);
        $day=array();
        for($i=1;$i<$con;$i++){
            for($d=1;$d<=5;$d++){
                if(count($arr[$i])==72){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                    if(trim($arr[$i][66])==$d){
                        $day[$d][]= "第".trim($arr[$i][67])."节有课:\n".trim($arr[$i][56])."\n".trim($arr[$i][70]).trim($arr[$i][71])."\n".trim($arr[$i][61]).trim($arr[$i][65]);
                    }
                }

                if(count($arr[$i])==54){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                    if(trim($arr[$i][48])==$d){
                        $day[$d][]= "第".trim($arr[$i][49])."节有课:\n".trim($arr[$i][38])."\n".trim($arr[$i][51]).trim($arr[$i][52])."\n".trim($arr[$i][43]).trim($arr[$i][47]);
                    }
                }

                if(count($arr[$i])==18){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                    if(trim($arr[$i][12])==$d){
                        $day[$d][]= "第".trim($arr[$i][13])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][16]).trim($arr[$i][17])."\n".trim($arr[$i][7]).trim($arr[$i][11]);
                    }
                }
                //开始
                if(count($arr[$i])==36){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                if(trim($arr[$i][30])==$d){
                    //$day[$d][]= "第".trim($arr[$i][12])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][15]).trim($arr[$i][16])."\n".trim($arr[$i][7]).trim($arr[$i][10]);
                    $day[$d][]=array();
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][31])){
                            $day[$d][]= "第".trim($arr[$i][31])."节有课:\n".trim($arr[$i][20])."\n".trim($arr[$i][34]).trim($arr[$i][35])."\n".trim($arr[$i][25]).trim($arr[$i][29]);
                        }
                        
                    }
                }
                }
                //结束
                if(count($arr[$i])==7){
                    if(trim($arr[$i][1])==$d){
                      if(strlen(trim($arr[$i-1][2]))<2){
                        if(strlen(trim($arr[$i-2][2]))<2){
                            //开始
                              $day[$d][]= "第".trim($arr[$i][2])."节有课:\n".trim($arr[$i-3][2])."\n".trim($arr[$i][5]).trim($arr[$i][6])."\n".trim($arr[$i-3][7]).trim($arr[$i][0]);
                            }else{
                                //结束
                                $day[$d][]= "第".trim($arr[$i][2])."节有课:\n".trim($arr[$i-2][2])."\n".trim($arr[$i][5]).trim($arr[$i][6])."\n".trim($arr[$i-2][7]).trim($arr[$i][0]);
                            }
                          
                      }else{
                        $day[$d][]= "第".trim($arr[$i][2])."节有课:\n".trim($arr[$i-1][2])."\n".trim($arr[$i][5]).trim($arr[$i][6])."\n".trim($arr[$i-1][7]).trim($arr[$i][0]);
                        }
                      }
                      
                    
                }
            }    
        }
        //print_r($day);
        $day=json_encode($day);
        //$day=addslashes($day);    
        return $day;
    }
    //网页版课表
    function getXuanke2(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $url="https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xkAction.do?actionType=6";
        //$url = "https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do?zxjxjhh=2015-2016-2-1";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $content=iconv("gbk", "utf-8", $content);

        $html=new SimpleHtmlController();

        $content=str_replace("一","1",$content);
        $content=str_replace("二","2",$content);
        $content=str_replace("三","3",$content);
        $content=str_replace("四","4",$content);
        $content=str_replace("五","5",$content);
        $content=str_replace("六","6",$content);
        $content=str_replace("七","7",$content);
        $content=str_replace("八","8",$content);
        $content=str_replace("九","9",$content);

        $html->load($content);
        $table=$html->find('table')[7];
        $arr=$this->get_td_array($table);//执行函数
        //第一步获取到所有的课程一个课程放到一个数组中去
        $data = array();
        for($i=1;$i<count($arr);$i++){
            if((count($arr[$i])%18)==0){
                if(count($arr[$i])>18){
                    $data[$i]=array_chunk($arr[$i], 18);
                }else{
                    $data[$i][]=$arr[$i];
                    if(count($arr[$i+1])==7){
                        $data[$i][]=$arr[$i+1];
                    }
                    if(count($arr[$i+1])==7&&count($arr[$i+2])==7){
                        $data[$i][]=$arr[$i+2];
                    }
                    if(count($arr[$i+2])==7&&count($arr[$i+3])==7){
                        $data[$i][]=$arr[$i+3];
                    }
                }   
            }
        }
        //return $data;
        $js_data=array();
        foreach($data as $k=>$v){
            for($i=0;$i<count($v);$i++){
                if(count($data[$k][$i])==18){
                    $js_data[$data[$k][$i][12]][trim($data[$k][$i][13])]['score'][]=trim($data[$k][$i][2]);
                    $js_data[$data[$k][$i][12]][trim($data[$k][$i][13])]['teacher'][]=trim($data[$k][$i][7]).trim($data[$k][$i][11]);
                    $js_data[$data[$k][$i][12]][trim($data[$k][$i][13])]['add'][]=trim($data[$k][$i][16].$data[$k][$i][17]);
                    $js_data[$data[$k][$i][12]][trim($data[$k][$i][13])]['week'][]=trim($data[$k][$i][11]);
                }else{
                    $js_data[$data[$k][$i][1]][trim($data[$k][$i][2])]['score'][]=trim($data[$k][0][2]);
                    $js_data[$data[$k][$i][1]][trim($data[$k][$i][2])]['teacher'][]=trim($data[$k][0][7]).trim($data[$k][$i][0]);
                    $js_data[$data[$k][$i][1]][trim($data[$k][$i][2])]['add'][]=trim($data[$k][$i][5].$data[$k][$i][6]);
                    $js_data[$data[$k][$i][1]][trim($data[$k][$i][2])]['week'][]=trim($data[$k][$i][0]);
                }
            }
        }
        $js_data=json_encode($js_data);
        return $js_data;

        $con=count($arr);
        //网页版课程表
        $day=array();
        for($i=1;$i<$con;$i++){
            for($d=1;$d<=5;$d++){
                if(count($arr[$i])==72){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                if(trim($arr[$i][66])==$d){
                    //$day[$d][]= "第".trim($arr[$i][12])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][15]).trim($arr[$i][16])."\n".trim($arr[$i][7]).trim($arr[$i][10]);
                    $day[$d][]=array();
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][67])){
                            $day[$d][$j]['score'][]=trim($arr[$i][56]);
                            $day[$d][$j]['add'][]=trim($arr[$i][70]).trim($arr[$i][71]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i][61]).trim($arr[$i][65]);
                        }
                        
                    }
                }
                }

                if(count($arr[$i])==54){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                if(trim($arr[$i][48])==$d){
                    //$day[$d][]= "第".trim($arr[$i][12])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][15]).trim($arr[$i][16])."\n".trim($arr[$i][7]).trim($arr[$i][10]);
                    $day[$d][]=array();
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][49])){
                            $day[$d][$j]['score'][]=trim($arr[$i][38]);
                            $day[$d][$j]['add'][]=trim($arr[$i][51]).trim($arr[$i][52]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i][43]).trim($arr[$i][47]);
                        }
                        
                    }
                }
                }

                if(count($arr[$i])==36){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                if(trim($arr[$i][30])==$d){
                    //$day[$d][]= "第".trim($arr[$i][12])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][15]).trim($arr[$i][16])."\n".trim($arr[$i][7]).trim($arr[$i][10]);
                    $day[$d][]=array();
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][31])){
                            $day[$d][$j]['score'][]=trim($arr[$i][20]);
                            $day[$d][$j]['add'][]=trim($arr[$i][34]).trim($arr[$i][35]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i][25]).trim($arr[$i][29]);
                        }
                        
                    }
                }
                }
                if(count($arr[$i])==18){
                //echo $arr[$i][2].$arr[$i][7]."\n";
                if(trim($arr[$i][12])==$d){
                    //$day[$d][]= "第".trim($arr[$i][12])."节有课:\n".trim($arr[$i][2])."\n".trim($arr[$i][15]).trim($arr[$i][16])."\n".trim($arr[$i][7]).trim($arr[$i][10]);
                    $day[$d][]=array();
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][13])){
                            $day[$d][$j]['score'][]=trim($arr[$i][2]);
                            $day[$d][$j]['add'][]=trim($arr[$i][16]).trim($arr[$i][17]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i][7]).trim($arr[$i][11]);
                        }
                        
                    }
                }
                }
                if(count($arr[$i])==7){
                    if(trim($arr[$i][1])==$d){
                    //$day[$d][]= "第".trim($arr[$i][2])."节有课:\n".trim($arr[$i-1][2])."\n".trim($arr[$i][5]).trim($arr[$i][6])."\n".trim($arr[$i-1][7]).trim($arr[$i][0]);
                    /*for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][2])){
                            if(strlen(trim($arr[$i-1][2]))<2){
                                $day[$d][$j]['score'][]=trim($arr[$i-2][2]);
                                $day[$d][$j]['teacher'][]=trim($arr[$i-2][7]).trim($arr[$i][0]);
                            }else{
                                $day[$d][$j]['score'][]=trim($arr[$i-1][2]);
                                $day[$d][$j]['teacher'][]=trim($arr[$i-1][7]).trim($arr[$i][0]);
                            }
                            $day[$d][$j]['add'][]=trim($arr[$i][5]).trim($arr[$i][6]);
                            
                        }
                        
                    }*/
                    for($j=1;$j<=9;$j++){
                        if($j==trim($arr[$i][2])){
                          if(strlen(trim($arr[$i-1][2]))<2){
                            if(strlen(trim($arr[$i-2][2]))<2){
                              $day[$d][$j]['score'][]=trim($arr[$i-3][2]);
                              $day[$d][$j]['teacher'][]=trim($arr[$i-3][7]).trim($arr[$i][0]);
                            }else{
                              $day[$d][$j]['score'][]=trim($arr[$i-2][2]);
                              $day[$d][$j]['teacher'][]=trim($arr[$i-2][7]).trim($arr[$i][0]);
                            }
                            
                          }else{
                            $day[$d][$j]['score'][]=trim($arr[$i-1][2]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i-1][7]).trim($arr[$i][0]);
                          }
                            
                            $day[$d][$j]['add'][]=trim($arr[$i][5]).trim($arr[$i][6]);
                            $day[$d][$j]['teacher'][]=trim($arr[$i-1][6]).trim($arr[$i][0]);
                        }
                        
                    }
                }
                }
            }    
        }
        $day=json_encode($day);
        //$day=addslashes($day);    
        return $day;
    }

    //源课表
    function getXuanke3(){
        if(isset($_COOKIE['isl'])){
            $session4="websvr_cookie"."=".$_COOKIE['websvr_cookie'];
            $session2="ENABLE_RANDCODE"."=".$_COOKIE['ENABLE_RANDCODE'];
            $session3="TWFID"."=".$_COOKIE['TWFID'];
        }
        $url="https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/xkAction.do?actionType=6";
        //$url = "https://vpn.hpu.edu.cn/web/1/http/2/218.196.240.97/lnkbcxAction.do?zxjxjhh=2015-2016-2-1";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 10.0; WOW64; Trident/7.0; Touch; .NET4.0C; .NET4.0E; Tablet PC 2.0; InfoPath.3)");
        curl_setopt($ch,CURLOPT_COOKIE,"$session2;$session3;$session4");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $content=iconv("gbk", "utf-8", $content);
        $html=new SimpleHtmlController();
        $html->load($content);
        $content=$html->find('table')[4];
        $content=html_entity_decode($content);
        return $content;
        setcookie('websvr_cookie', NULL);
        setcookie('ENABLE_RANDCODE', NULL);
        setcookie('TWFID', NULL);
    }

    //获取历年成绩
    /*public function getLiNian(){
        $user=M('user');
        $openid=get_openid();
        $mm = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $mm = substr($mm, 11, 6);
        $studentid=$user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $rs=curl_init();
        //post提交
        $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
        $post="Login.Token1=".$studentid."&Login.Token2=".$mm."&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
        curl_setopt($rs,CURLOPT_URL,$url);
        //post数据来源
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/login.portal");
        curl_setopt($rs,CURLOPT_POST,1);
        curl_setopt($rs,CURLOPT_POSTFIELDS,$post);
        //设置cookie
        curl_setopt($rs,CURLOPT_COOKIESESSION,1);
        curl_setopt($rs,CURLOPT_COOKIEFILE,$mm);
        curl_setopt($rs,CURLOPT_COOKIEJAR, $mm);
        curl_setopt($rs,CURLOPT_COOKIE,session_name().'='.session_id());
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        //跳转到数据页面
        curl_exec($rs);
        curl_setopt($rs,CURLOPT_URL,"http://xqfx.hpu.edu.cn:9080/xqfx/grinfo/xsxx_self/xscjinfo.jsp");
        curl_setopt($rs,CURLOPT_REFERER,"http://xqfx.hpu.edu.cn:9080/xqfx/grinfo/xsxx_self/frameset.jsp");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        $html=new SimpleHtmlController();
        $html->load($content);
        $table=$html->find('table')[0];
        $arr=$this->get_td_array($table);//执行函数
        $arr=json_encode($arr);
        $arr=addslashes($arr);    
        return $arr;
    }*/
    
    
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
   
}

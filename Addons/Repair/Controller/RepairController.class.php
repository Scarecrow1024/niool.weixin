<?php
namespace Addons\Repair\Controller;
use Home\Controller\AddonsController;

class RepairController extends AddonsController{
    public function index(){
        $openid=$_GET['openid'];
        //先登陆
        $user=M('user');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $IdCard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $mm = substr($IdCard, 11, 6);
        $log_url="http://repair.hpu.edu.cn/pc/Account/Login";
        $log_post="UserName=".$studentid."&Password=".$mm."&ReturnUrl=http%3A%2F%2Frepair.hpu.edu.cn%2Fpc%2F&LoginType=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$log_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$log_post);
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/pc/Account/login");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie1=$str[1][0];
        $cookie2=$str[1][1];
        curl_close($ch);

        //判断是否填写个人资料
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://repair.hpu.edu.cn/rsp/my/info");
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/rsp/my/repaired");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie1;$cookie2");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        //echo $content;
        preg_match_all('|value="(.*)"|isU',$content,$data);
        $phone=$data[1][5];
        //print_r($data);
        if((($data[1][5]=='')||($data[1][6]==''))){
            $this->error("等待跳转完善个人信息后再来报修",addons_url ("Repair://Repair/grzx/openid/".$openid),1);
        }else{
            $this->assign('phone',$phone);
            $this->assign('openid',$openid);
            $this->display();
        }
    
    }

    public function index2(){
        $this->display();
    }

    public function nqrepair(){
        /*$openid=get_openid();
        //先登陆
        $user=M('user');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $IdCard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $mm = substr($IdCard, 11, 6);*/
        $studentid = '311309010125';
        $mm = '190031';
        $log_url="http://218.196.240.133/nqwx/index.jsp";
        $log_post="username=".$studentid."&password=".$mm."&Submit2=%B5%C7%C2%BD&ihide=yhide";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$log_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$log_post);
        curl_setopt($ch,CURLOPT_REFERER,"http://218.196.240.133/nqwx/");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$cookies); //正则匹配  
        $cookie1=$cookies[1][0];
        curl_close($ch);

        $post = "bxfws=".$_POST['bxfws']."&bxqy=".$_POST['bxqy']."&bxly=".$_POST['bxly']."&mphm=".$_POST['mphm']."&fjlx=".$_POST['fjlx']."&bxlx=".$_POST['bxlx']."&bxnr=".$_POST['bxnr']."&reporttime=".$_POST['reporttime']."&reporter=".$_POST['reporter']."&reporttels=".$_POST['reporttels']."&content=".iconv('utf-8','gbk',$_POST['content'])."&rstate=ystate";
        $repair_url = "http://218.196.240.133/nqwx/report.jsp"; 
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$repair_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie1");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_REFERER,"http://218.196.240.133/nqwx/report.jsp?user=".$studentid);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content2=curl_exec($ch);
        curl_close($ch);
        if(strpos($content2,'200')){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function nqdata(){
        if($_GET['id']=='80'){
            echo '[{"name":"1#","value":"92"},{"name":"2#","value":"93"},{"name":"3#","value":"94"},{"name":"4#","value":"95"},{"name":"5#","value":"96"},{"name":"6#","value":"97"},{"name":"2#研究生楼","value":"98"}]';
        }elseif($_GET['id']=='33'){
            echo '[{"name":"1#","value":"89"},{"name":"2#","value":"90"},{"name":"3#","value":"91"}]';
        }elseif($_GET['id']=='32'){
            echo '[{"name":"1#","value":"81"},{"name":"2#","value":"82"},{"name":"3#","value":"83"},{"name":"4#","value":"84"},{"name":"5#","value":"85"},{"name":"6#","value":"86"},{"name":"7#","value":"87"},{"name":"8#","value":"88"}]';
        }elseif($_GET['id']=='99'){
            echo '[{"name":"1#","value":"100"},{"name":"2#","value":"101"},{"name":"3#","value":"102"},{"name":"4#","value":"103"},{"name":"5#","value":"104"},{"name":"6#","value":"105"},{"name":"7#","value":"106"},{"name":"8#","value":"107"}]';
        }
    }

    public function grzx(){
        $openid=$_GET['openid'];
        $user=M('user');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $this->assign('openid',$openid);
        $this->assign('studentid',$studentid);
        $this->display();
    }

    public function update(){
        $openid=$_POST['openid'];
        $user=M('user');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $IdCard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $mm = substr($IdCard, 11, 6);
        if(isset($_POST['submit'])){
            if(strlen($_POST['phone'])<11){
                $this->error('手机号长度不对');
            }else if(strlen($_POST['nickname'])==""){
                $this->error('昵称不能为空');
            }else if(strlen($_POST['address'])==""){
                $this->error('地址不能为空');
            }
        }
        $log_url="http://repair.hpu.edu.cn/pc/Account/Login";
        $log_post="UserName=".$studentid."&Password=".$mm."&ReturnUrl=http%3A%2F%2Frepair.hpu.edu.cn%2Fpc%2F&LoginType=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$log_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$log_post);
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/pc/Account/login");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie1=$str[1][0];
        $cookie2=$str[1][1];
        curl_close($ch);

        $post="Nickname=".$_POST['nickname']."&Mobile=".$_POST['phone']."&Address=".$_POST['address']."&MID=".$studentid;
        $url = "http://repair.hpu.edu.cn/rsp/my/info";
        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/rsp/my/info");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie1;$cookie2");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        $this->success('个人资料修改成功',addons_url ("Repair://Repair/index/openid/".$openid),1);
    }
    
    public function repair(){
        //先登陆
        $openid=$_POST['openid'];
        $user=M('user');
        $studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');

        $name = 'HPU';
        $IdCard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $mm = substr($IdCard, 11, 6);
        $log_url="http://repair.hpu.edu.cn/pc/Account/Login";
        $log_post="UserName=".$studentid."&Password=".$mm."&ReturnUrl=http%3A%2F%2Frepair.hpu.edu.cn%2Fpc%2F&LoginType=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$log_url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$log_post);
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/pc/Account/login");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        //正则匹配cookie并使用
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配  
        $cookie1=$str[1][0];
        $cookie2=$str[1][1];
        curl_close($ch);


        //获取pwdstr
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://repair.hpu.edu.cn/rsp/my/wantrepair");
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/rsp/site/index");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie1;$cookie2");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        //echo $content;
        preg_match_all('|value="(.*)"|isU',$content,$arr);
        $pwdstr = $arr[1][27];

        $Area_Serial=$_POST['Area_Serial'];
        $Area_Name=$_POST['Area_Name'];
        $Baddress=$_POST['Baddress'];
        $Project_Serial=$_POST['Project_Serial'];
        $Project_Name=$_POST['Project_Name'];
        $Bcontent=$_POST['Bcontent'];
        $Mobile=$_POST['Mobile'];
        $BuserName=$_POST['BuserName'];
        $Bsource=$_POST['Bsource'];
        $post="Area_Serial=".$Area_Serial."&Area_Name=".$Area_Name."&Baddress=".$Baddress."&Project_Name=".$Project_Name."&Project_Serial=".$Project_Serial."&Bcontent=".$Bcontent."&Mobile=".$Mobile."&BuserName=".$name."小微"."&InfoID=&pwdstr=".$pwdstr."&imglist=&Bsource=".$Bsource;
        $url = "http://repair.hpu.edu.cn/rsp/my/wantrepair";
        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_REFERER,"http://repair.hpu.edu.cn/rsp/my/wantrepair");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_COOKIE,"$cookie1;$cookie2");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec ( $ch );
        curl_close ( $ch );
        //echo $content;
        echo "ヾ(o◕∀◕)ﾉヾ报修成功";
    }
   
}

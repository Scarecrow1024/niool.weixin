<?php

namespace Addons\Ping\Controller;
use Home\Controller\AddonsController;

class PingController extends AddonsController{
    public function verify1(){
        //$cookiefile=dirname(__FILE__)."/pic.cookie";
        $url = "http://218.196.240.112/jwweb/default.aspx";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch,CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        preg_match_all('/Set-Cookie:(.*);/iU',$content,$cookies); //正则匹配 
        $cookie=$cookies[1][0];
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie");
        preg_match_all('|value="(.*)"|isU',$content,$arr);
        $__VIEWSTATE=$arr[1][0];
        $__EVENTVALIDATION=$arr[1][2];
        curl_close($ch);
        $arr=explode("=", $cookie);
        $cookie=$arr[0].'='.$arr[1];
        setcookie("ASP.NET_SessionId",trim($arr[1]));
        setcookie("__VIEWSTATE",$__VIEWSTATE);
        setcookie("__EVENTVALIDATION",$__EVENTVALIDATION);

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,'http://218.196.240.112/jwweb/dimage1.aspx');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $cookie="ASP.NET_SessionId=".$_COOKIE['ASP.NET_SessionId'];
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $str=curl_exec($ch);
        echo $str;
    }

    public function ping1(){
        //$cookiefile=dirname(__FILE__)."/pic.cookie";
        $url = "http://218.196.240.112/jwweb/default.aspx";
        $post = "TextBox1=".$_POST['TextBox1']."&TextBox2=".$_POST['TextBox2']."&TextBox3=".$_POST['TextBox3']."&__EVENTVALIDATION=".$_COOKIE['__EVENTVALIDATION']."&__VIEWSTATE=".$_COOKIE['__VIEWSTATE']."&Button1".$_POST['Button1'];   
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_REFERER,'http://218.196.240.112/jwweb/default.aspx');
        $cookie="ASP.NET_SessionId=".$_COOKIE['ASP.NET_SessionId'];
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie");
        //curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($ch);
        curl_close($ch);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_REFERER,'http://218.196.240.112/jwweb/default.aspx');
        curl_setopt($ch, CURLOPT_URL, "http://218.196.240.112/jwweb/eval.aspx");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);        
        $cookie="ASP.NET_SessionId=".$_COOKIE['ASP.NET_SessionId'];
        curl_setopt($ch, CURLOPT_COOKIE, "$cookie");
        $html=curl_exec($ch);
        curl_close($ch);
        echo $html;
    }

	public function index(){
        
        $this->display('login');
	}

    public function ping(){
        $snoopy=new SnoopyController();
        $snoopy->referer='http://218.196.240.112/jwweb/default.aspx';
        $snoopy->cookies["ASP.NET_SessionId"] = $_COOKIE['ASP.NET_SessionId'];
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36";
        $post['__VIEWSTATE']=$_POST['__VIEWSTATE'];
        $post['Button1']=$_POST['Button1'];
        $post['__EVENTVALIDATION']=$_POST['__EVENTVALIDATION'];
        $post['TextBox1']=$_POST['TextBox1'];
        $post['TextBox2']=$_POST['TextBox2'];
        $post['TextBox3']=$_POST['TextBox3'];

        $snoopy->cookies["ASP.NET_SessionId"] = $_COOKIE['ASP.NET_SessionId'];
        $url='http://218.196.240.112/jwweb/default.aspx';//登陆数据提交的URL地址
        $snoopy->submit($url,$post);
        $snoopy->fetch("http://218.196.240.112/jwweb/eval.aspx");//希望获取的页面数据
        $content = $snoopy->results;//输出结果，登陆成功
        echo $content;
    }

    public function cookie(){
        set_time_limit(0);
        $snoopy = new SnoopyController();
        $snoopy->fetch('http://218.196.240.112/jwweb/default.aspx'); //获取所有内容
        $content = $snoopy->results;
        preg_match_all('|value="(.*)"|isU',$content,$arr); //匹配到数组$arr中；

        setcookie('__VIEWSTATE',$arr[1][0]);
        setcookie('__EVENTVALIDATION',$arr[1][2]);

        $cookies1=$snoopy->headers;
        preg_match('/Set-Cookie:(.*);/iU',$cookies1[6],$cookies1); //正则匹配  
        $arr1=explode('=', $cookies1[1]);
        $cookie1=$arr1[1];
        setcookie('ASP.NET_SessionId',$cookie1);
    }

    public function verify(){
        $this->cookie();
        $snoopy = new SnoopyController();
        $snoopy->referer='http://218.196.240.112/jwweb/default.aspx';//例如：http://www.baidu.com/
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36";
        $snoopy->cookies["ASP.NET_SessionId"] = $cookie1;
        $snoopy->fetch("http://218.196.240.112/jwweb/dimage1.aspx");//希望获取的页面数据
        echo $snoopy->results;
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
   
}

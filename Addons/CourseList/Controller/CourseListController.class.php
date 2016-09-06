<?php

namespace Addons\CourseList\Controller;
use Home\Controller\AddonsController;

class CourseListController extends AddonsController{
    //获取网页版课表
    public function get_zhou(){
        $rs=curl_init();
        $user=M('user');
        $openid=get_openid();
        //$studentid = $user->where("openid=".'"'.$openid.'"')->getField('studentid');
        $data = $user->where("openid=".'"'.$openid.'"')->find();
        //$idcard = $user->where("openid=".'"'.$openid.'"')->getField('IdCard');
        $idcard=$data['IdCard'];
        $studentid=$data['studentid'];
        $idcard=substr($idcard, 11, 6);
        //post提交
        $url="http://my.hpu.edu.cn/userPasswordValidate.portal";
        $post="Login.Token1=".$studentid."&Login.Token2=".$idcard."&goto=http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal&gotoOnFail=http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal"; 
        curl_setopt($rs,CURLOPT_URL,$url);
        //post数据来源
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/login.portal");
        curl_setopt($rs,CURLOPT_POST,1);
        curl_setopt($rs,CURLOPT_POSTFIELDS,$post);
        //设置cookie
        curl_setopt($rs,CURLOPT_COOKIESESSION,1);
        curl_setopt($rs,CURLOPT_COOKIEFILE,"cookie");
        curl_setopt($rs,CURLOPT_COOKIEJAR, "cookie");
        curl_setopt($rs,CURLOPT_COOKIE,session_name().'='.session_id());
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        //跳转到数据页面
        curl_exec($rs);
        curl_setopt($rs,CURLOPT_URL,"http://my.hpu.edu.cn/pnull.portal?.p=Znxjb20ud2lzY29tLnBvcnRhbC5zaXRlLnYyLmltcGwuRnJhZ21lbnRXaW5kb3d8ZjEyMDd8dmlld3xub3JtYWx8&.nctp=true&.ll=true&.reqId=454292a2-805e-11e5-8ee7-93b0a51465ca&.isTW=false");
        curl_setopt($rs,CURLOPT_REFERER,"http://my.hpu.edu.cn/index.portal");
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        $content=curl_exec($rs);
        curl_close($rs);
        //echo $content;
        //$html = new simple_html_dom();
        $content=strip_tags($content)."<br>";
        preg_match_all("/[0-9]+/", $content, $matches);
        return $matches[0][7];
    }
    public function course(){
        $openid=get_openid();
        $user=M('user');
        $data=$user->where("openid=".'"'.$openid.'"')->getField('webcourse');
        $day=json_decode($data,true);    
        $this->assign('day',$day);
        $this->assign('zhou',$this->get_zhou());
        $this->display();        
    }
    //获取教务处课表
    public function ycourse(){
        $openid=get_openid();
        $user=M('user');
        $day=$user->where("openid=".'"'.$openid.'"')->getField('yscore'); 
        $this->assign('day',$day);
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
   
}

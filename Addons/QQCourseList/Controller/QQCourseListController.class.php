<?php

namespace Addons\QQCourseList\Controller;
use Home\Controller\AddonsController;

class QQCourseListController extends AddonsController{
    //获取网页版课表
    public function course(){
        $openid=$_SESSION['openid'];
        $user=M('qqcourselist');
        //$data=$user->where("openid=".'"'.$openid.'"')->getField('courselist');
        $data = $user->where('id=22')->getField('courselist');
        $day=json_decode($data,true); 
        $this->assign('day',$day);
        $this->display();        
    }
    //获取教务处课表
    public function ycourse(){
        $openid=$_SESSION['openid'];
        $user=M('qqcourselist');
        $day=$user->where("openid=".'"'.$openid.'"')->getField('ycourse'); 
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

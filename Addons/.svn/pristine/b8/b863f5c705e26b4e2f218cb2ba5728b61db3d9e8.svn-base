<?php

namespace Addons\Houqin\Controller;
use Home\Controller\AddonsController;

class HouqinController extends AddonsController{
    public function json_build(){
        if($_GET['id']=='4'){
            echo '[{"name":"3","value":"3#"},{"name":"4","value":"4#"},{"name":"5","value":"5#"},{"name":"6","value":"6#"},{"name":"7","value":"7#"},{"name":"8","value":"8#"},{"name":"9","value":"9#"},{"name":"10","value":"10#"},{"name":"12","value":"12#"},{"name":"14","value":"14#"}]';
        }elseif($_GET['id']=='2'){
            echo '[{"name":"1","value":"1#"},{"name":"2","value":"2#"},{"name":"3","value":"3#"},{"name":"4","value":"4#"},{"name":"5","value":"5#"},{"name":"6","value":"6#"},{"name":"7","value":"7#"},{"name":"8","value":"8#"},{"name":"9","value":"9#"},{"name":"10","value":"10#"},{"name":"11","value":"11#"},{"name":"12","value":"12#"},{"name":"13","value":"13#"},{"name":"14","value":"14#"}]';
        }elseif($_GET['id']=='3'){
            echo '[{"name":"1","value":"1#"},{"name":"2","value":"2#"},{"name":"3","value":"3#"},{"name":"4","value":"4#"},{"name":"5","value":"5#"},{"name":"6","value":"6#"},{"name":"7","value":"7#"},{"name":"8","value":"8#"}]';
        }elseif($_GET['id']=='1'){
            echo '[{"name":"1","value":"1#"},{"name":"2","value":"2#"},{"name":"3","value":"3#"},{"name":"4","value":"4#"},{"name":"5","value":"5#"},{"name":"6","value":"6#"},{"name":"7","value":"7#"},{"name":"8","value":"8#"},{"name":"9","value":"9#"}]';
        }
    }

    public function json_hourse(){
        if($_GET['id']=='1'){
            echo '[{"name":"111","value":"111"},{"name":"112","value":"112"},{"name":"121","value":"121"},{"name":"122","value":"122"},{"name":"131","value":"131"},{"name":"132","value":"132"},{"name":"141","value":"141"},{"name":"142","value":"142"},{"name":"151","value":"151"},{"name":"152","value":"152"},{"name":"161","value":"161"},{"name":"162","value":"162"}]';
        }elseif($_GET['id']=='2'){
            echo '[{"name":"211","value":"211"},{"name":"212","value":"212"},{"name":"221","value":"221"},{"name":"222","value":"222"},{"name":"231","value":"231"},{"name":"232","value":"232"},{"name":"241","value":"241"},{"name":"242","value":"242"},{"name":"251","value":"251"},{"name":"252","value":"252"},{"name":"261","value":"261"},{"name":"262","value":"262"}]';
        }elseif($_GET['id']=='3'){
            echo '[{"name":"311","value":"311"},{"name":"312","value":"312"},{"name":"321","value":"321"},{"name":"322","value":"322"},{"name":"331","value":"331"},{"name":"332","value":"332"},{"name":"341","value":"341"},{"name":"342","value":"342"},{"name":"351","value":"351"},{"name":"352","value":"352"},{"name":"361","value":"361"},{"name":"362","value":"362"}]';
        }elseif($_GET['id']=='4'){
            echo '[{"name":"411","value":"411"},{"name":"412","value":"412"},{"name":"421","value":"421"},{"name":"422","value":"422"},{"name":"431","value":"431"},{"name":"432","value":"432"},{"name":"441","value":"441"},{"name":"442","value":"442"},{"name":"451","value":"451"},{"name":"452","value":"452"},{"name":"461","value":"461"},{"name":"462","value":"462"}]';
        }elseif($_GET['id']=='5'){
            echo '[{"name":"511","value":"511"},{"name":"512","value":"512"},{"name":"521","value":"521"},{"name":"522","value":"522"},{"name":"531","value":"531"},{"name":"532","value":"532"},{"name":"541","value":"541"},{"name":"542","value":"542"},{"name":"551","value":"551"},{"name":"552","value":"552"},{"name":"561","value":"561"},{"name":"562","value":"562"}]';
        }elseif($_GET['id']=='6'){
            echo '[{"name":"611","value":"611"},{"name":"612","value":"612"},{"name":"621","value":"621"},{"name":"622","value":"622"},{"name":"631","value":"631"},{"name":"632","value":"632"},{"name":"641","value":"641"},{"name":"642","value":"642"},{"name":"651","value":"651"},{"name":"652","value":"652"},{"name":"661","value":"661"},{"name":"662","value":"662"}]';
        }elseif($_GET['id']=='7'){
            echo '[{"name":"711","value":"711"},{"name":"712","value":"712"},{"name":"721","value":"721"},{"name":"22","value":"722"},{"name":"731","value":"731"},{"name":"732","value":"732"},{"name":"741","value":"741"},{"name":"742","value":"742"},{"name":"751","value":"751"},{"name":"752","value":"752"},{"name":"761","value":"761"},{"name":"762","value":"762"}]';
        }
    }

    public function login(){
        $snoopy = new SnoopyController();
        $snoopy->fetch('http://218.196.246.130/weh/web/login.asp');
        $headers=$snoopy->headers;
        //print_r($headers);
        preg_match('/Set-Cookie:(.*);/iU',$headers[7],$cookie1);
        //print_r($cookie1);
        $arr=explode("=", $cookie1[1]);
        setcookie('cookie_name',$arr[0]);
        setcookie('cookie_value',$arr[1]);
        if(!empty($_COOKIE)){
            $this->display('do_login');
        }else{
            header('location:http://niool.com/weixin/index.php?s=/addon/Houqin/Houqin/login.html');
        }
        
    }
    
    public function do_login(){
        //post家属院
        $snoopy = new SnoopyController();
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $snoopy->referer='http://218.196.246.130/weh/web/login.asp';
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0";
        $post['yuan_id']=I('post.yuan_id');
        $post['build_id']=0;
        $post['unit_id']=0;
        $post['flag']=1;
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $url='http://218.196.246.130/weh/web/houseresult.asp?yuan_id=4&build_id=0&unit_id=0&flag=1';
        $snoopy->submit($url,$post);

        //post家属楼
        $snoopy = new SnoopyController();
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $snoopy->referer='http://218.196.246.130/weh/web/login.asp';
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0";
        $post['yuan_id']=I('post.yuan_id');
        $post['build_id']=I('post.build_id');
        $post['unit_id']=0;
        $post['flag']=2;
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $url='http://218.196.246.130/weh/web/houseresult.asp?yuan_id=4&build_id=8&unit_id=0&flag=2';
        $snoopy->submit($url,$post);

        //post单元号
        $snoopy = new SnoopyController();
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $snoopy->referer='http://218.196.246.130/weh/web/login.asp';
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0";
        $post['yuan_id']=I('post.yuan_id');
        $post['build_id']=I('post.build_id');
        $post['unit_id']=I('post.unit_id');
        $post['flag']=3;
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $url='http://218.196.246.130/weh/web/houseresult.asp?yuan_id=4&build_id=8&unit_id=3&flag=3';
        $snoopy->submit($url,$post);

        //post所有的数据
        $snoopy = new SnoopyController();
        $snoopy->referer='http://218.196.246.130/weh/web/login.asp';
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0";
        $post['user_type']=I('post.user_type');
        $post['yuan_id']=I('post.yuan_id');
        $post['build_id']=I('post.build_id');
        $post['unit_id']=I('post.unit_id');
        $post['house_id']=I('post.house_id');
        $post['user_name']=I('post.user_name');
        $post['user_pwd']=I('post.user_pwd');
        $post['Submit']=I('post.Submit');
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $url='http://218.196.246.130/weh/web/checkuserlogin.asp';
        $snoopy->submit($url,$post);
        
        //获取电费
        $snoopy = new SnoopyController();
        $snoopy->cookies[$_COOKIE['cookie_name']] = $_COOKIE['cookie_value'];
        $snoopy->referer='http://218.196.246.130/weh/web/list.asp';
        $snoopy->agent="Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0";
        $snoopy->fetch('http://218.196.246.130/weh/web/weh_house_e_data.asp');
        $content=$snoopy->results;
        $str=iconv('gbk', 'utf-8', $content);
        $dianfei=$this->get_td_array($str);
        $con_dianfei=count($dianfei)-3;
        //获取水费
        $snoopy->fetch('http://218.196.246.130/weh/web/weh_house_w_data.asp');
        $content=$snoopy->results;
        $str=iconv('gbk', 'utf-8', $content);
        $shuifei=$this->get_td_array($str);
        $con_shuifei=count($shuifei)-3;
        //获取暖气费
        $snoopy->fetch('http://218.196.246.130/weh/web/weh_house_nq_data_cx.asp');
        $content=$snoopy->results;
        $str=iconv('gbk', 'utf-8', $content);
        $nuanqi=$this->get_td_array($str);
        $con_nuanqi=count($nuanqi)-1;
        //获取物业费
        $snoopy->fetch('http://218.196.246.130/weh/web/weh_house_wy_data.asp');
        $content=$snoopy->results;
        $str=iconv('gbk', 'utf-8', $content);
        $wuye=$this->get_td_array($str);
        $con_wuye=count($wuye)-3;
        $this->assign(
            array(
                'dianfei'=>$dianfei,
                'shuifei'=>$shuifei,
                'nuanqi'=>$nuanqi,
                'wuye'=>$wuye,
                'con_dianfei'=>$con_dianfei,
                'con_shuifei'=>$con_shuifei,
                'con_nuanqi'=>$con_nuanqi,
                'con_wuye'=>$con_wuye
                )
            );
        $this->display('list');
    }
    
    function get_td_array($table) { 
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

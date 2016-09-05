<?php 
	include('./qqConnectAPI.php');
	//echo $_GET['code']
	//获取access_token
	$oauth=new Oauth();
	$access_token=$oauth->qq_callback();
	//echo $access_token."<br>";
	//获取用户的唯一标示openid
	$openid=$oauth->get_openid();
	//echo $openid;
	$qc=new QC($access_token,$openid);
	$info=$qc->get_user_info();

	session_start();
	$_SESSION['openid']=$openid;
	$_SESSION['nickname']=$info['nickname'];
	$_SESSION['figureurl_1']=$info['figureurl_1'];
	$_SESSION['figureurl_qq_2']=$info['figureurl_qq_2'];
	$_SESSION['gender']=$info['gender'];
	//setcookie('openid',$openid,time()+3600);

	$link=@mysql_connect("localhost","root","zyf941126") or die("连接数据库失败！"."</br>".mysql_error());
	mysql_select_db("weixin");
	$sql="select * from wp_qqcourselist where openid='$openid'";
	$result=mysql_query($sql,$link);
	$ret=mysql_fetch_assoc($result);
	if($ret!=false){
		$url="http://niool.com/weixin/index.php?s=/addon/QQCourseList/QQCourseList/course.html";
		header("location:$url");
	}else{
		$url="http://niool.com/weixin/index.php?s=/addon/QQBind/QQBind/login.html";
		header("location:$url");
	}

?>
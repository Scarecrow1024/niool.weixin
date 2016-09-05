<?php 
	include('./Connect2.1/qqConnectAPI.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php if(!isset($_COOKIE['qq_access_token'])||!isset($_COOKIE['qq_openid'])){?>
		<a href="qqlogin.php">QQ登录</a>
	<?php }else{?>
		<?php
			$qc=new QC($_COOKIE['qq_access_token'],$_COOKIE['qq_openid']);
			$info=$qc->get_user_info();
			echo "<pre>";
			print_r($info);
			echo "</pre>";
		?>
		<a href="qqlogout.php">退出登录</a>
	<?php }?>
</body>
</html>
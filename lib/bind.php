<?php
session_start();
require_once('config.inc.php');
require_once('class.passport.php');
require_once('func.sign.php');

if(isset($_POST['bind']))
{
	$bp = new baidu_passport($_POST['cookie']);
	$result = $bp->get_passport_info();
	$con = mysql_connect(DB_IP,DB_USERNAME,DB_PASSWORD);
	if(!$con)
	{
		die('account bind error.');
	}else{
		if(mysql_select_db(DB_NAME))
		{
			mysql_query('set names utf8');
			mysql_query('UPDATE tc_baiduinfo SET baidu_id="'.$result['baiduid'].'", avastar="'.$result['avatar'].'" WHERE tc_id="'.$_SESSION['u'].'"');
			$list = get_list($_POST['cookie']);
			for ($i=0; $i < count($list); $i++) { 
				for ($k=0; $k < count($list[$i]['url']); $k++) { 
					mysql_query('INSERT INTO tc_tieba(cookie,fid,url) VALUES("'.$_POST['cookie'].'","'.$list[$i]['balvid'][$k].'","'.$list[$i]['url'][$k].'")');
				}
			}
			echo '<p>account bind success!</p>
			<script type="text/javascript"> 
			setTimeout(window.location.href="../index.php",3000); 
			</script>';
		}
	}
}
?>
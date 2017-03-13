<?php
header("content-type:text/html;charset=utf-8"); 

$dsn="mysql:dbname=net_spider;host=localhost";
$db_user='root'; 
$db_pass='';

try{ 
	$pdo=new PDO($dsn,$db_user,$db_pass); 
}catch(PDOException $e){ 
	echo '数据库连接失败'.$e->getMessage(); 
} 
$pdo->query('set names utf8;'); 

require('phpQuery/phpQuery.php');

$p = 0;
$rows = 50;

while (1) {
	$ssql = "SELECT `type`,`username`,`url` FROM `net_url` ORDER BY `id` LIMIT %d,%d";
	$ssql = sprintf($ssql,$p*$rows,$rows);
	$ssql = "SELECT `type`,`username`,`url` FROM `net_url` WHERE username not in(select `username` from `net_user_info`) ORDER BY `id`  ";
	echo $ssql;
	$result = $pdo->query($ssql)->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($result as $key => $value) {
		phpQuery::newDocumentFile($value['url']);
		$doc_li = pq('.us-info li');
		foreach ($doc_li as $k => $val) {
			$info[] = pq($val)->text();
		}
		var_dump($info);

		$ussql = "SELECT `username` FROM `net_user_info` WHERE `username` = '%s'";
		$ussql = sprintf($ussql,$value['username']);
		$users = $pdo->query($ussql)->fetch(PDO::FETCH_ASSOC);
		if($users){
			break;
		}

		$isql = "INSERT INTO `net_user_info`(`type`, `username`, `years`, `qq`, `email`, `my_url`, `sina_weibo`, `wechat`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')";
		$isql = sprintf($isql,$value['type'],$value['username'],$info[0],$info[1],$info[2],$info[3],$info[4],$info[5]);
		echo $isql;
		$res=$pdo->exec($isql);

		unset($info);
		echo $value['username'] ." 的用户信息已获取...";
		echo "\r\n";

		phpQuery::$documents = array();
	
	}

	sleep(5);
	$p++;
}



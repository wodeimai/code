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

$urls = "http://www.ui.cn/list.html?p=";
$type = "作品";

for ($i=1; $i < 140; $i++) { 
	$url = $urls.$i;
	phpQuery::newDocumentFile($url);

	$doc_a = pq(".user a");
	foreach ($doc_a as $key => $value) {
		$url = pq($value)->attr('href');
		$url_md5 = md5($url);
		$username =  pq($value)->text();

		$ssql = "SELECT `url_md5` FROM `net_url` WHERE `url_md5` = '%s'";
		$ssql = sprintf($ssql, $url_md5);
		$result = $pdo->query($ssql)->fetch(PDO::FETCH_ASSOC);
		if($result){
			continue;
		}

		$isql = "INSERT INTO `net_url`(`type`, `username`, `url`, `url_md5`) VALUES ('%s','%s','%s','%s')";
		$isql = sprintf($isql, $type, $username, $url, $url_md5);
		$res=$pdo->exec($isql);

		echo $username . '：URL ' . $url;
		echo "\r\n";
	}
	phpQuery::$documents = array();
	sleep(5);
}



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

$url = "http://www.ui.cn/list.html?p=";
$type = "作品";

for ($i=1; $i < 140; $i++) { 
	$url = $url.$i;
	phpQuery::newDocumentFile($url);

	$doc_a = pq(".user a");
	foreach ($doc_a as $key => $value) {
		$url = pq($value)->attr('href');
		$url_md5 = md5($url);
		$username =  pq($value)->text();

		$ssql = "SELECT `url_md5` FROM `net_url` WHERE `url_md5` = " . $url_md5;
		$result = $pdo->query($ssql);
		if($result){
			break;
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

die;











// INITIALIZE IT
// phpQuery::newDocumentHTML($markup);
// phpQuery::newDocumentXML();
// phpQuery::newDocumentFileXHTML('test.html');
// phpQuery::newDocumentFilePHP('test.php');
// phpQuery::newDocument('test.xml', 'application/rss+xml');
// this one defaults to text/html in utf8
$doc = phpQuery::newDocument('<div/>');

// FILL IT
// array syntax works like ->find() here
$doc['div']->append('<ul></ul>');
// array set changes inner html
$doc['div ul'] = '<li>1</li> <li>2</li> <li>3</li>';

// MANIPULATE IT
$li = null;
// almost everything can be a chain
$doc['ul > li']
	->addClass('my-new-class')
	->filter(':last')
		->addClass('last-li')
// save it anywhere in the chain
		->toReference($li);

// SELECT DOCUMENT
// pq(); is using selected document as default
phpQuery::selectDocument($doc);
// documents are selected when created or by above method
// query all unordered lists in last selected document
$ul = pq('ul')->insertAfter('div');

// ITERATE IT
// all direct LIs from $ul
foreach($ul['> li'] as $li) {
	// iteration returns PLAIN dom nodes, NOT phpQuery objects
	$tagName = $li->tagName;
	$childNodes = $li->childNodes;
	// so you NEED to wrap it within phpQuery, using pq();
	pq($li)->addClass('my-second-new-class');
}

// PRINT OUTPUT
// 1st way
print phpQuery::getDocument($doc->getDocumentID());
// 2nd way
print phpQuery::getDocument(pq('div')->getDocumentID());
// 3rd way
print pq('div')->getDocument();
// 4th way
print $doc->htmlOuter();
// 5th way
print $doc;
// another...
print $doc['ul'];
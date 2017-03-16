<?php

namespace Go\Controller;

use Think\Controller;

/**
 * 采集控制器
 * 主要获取各个平台的聚合数据
 */
class CollectController extends Controller
{

    public function index()
    {
        Vendor('phpQuery.phpQuery.phpQuery');
        $url = "http://guangdiu.com/cate.php?p=1&k=baby";
        \phpQuery::newDocumentFile($url);
        $html = pq('.gooditem');
        foreach ($html as $key => $value) {
            $good['title'] = pq($value)->find('.iteminfoarea .goodname')->html();
            $good['desc'] = trim(pq($value)->find('.abstractcontent')->text());
            $good['site'] = pq($value)->find('.rightlinks .rightmallname')->text();
            $good['tongbu'] = pq($value)->find('.timeandfrom .infofrom')->text();
            $good['img'] = pq($value)->find('.imgandbtn img')->attr('src');
            $good['target_url'] = pq($value)->find('.rightlinks .innergototobuybtn')->attr('href');
            $good['from_id'] = substr($good['target_url'], strpos($good['target_url'], 'id=') + 3);
            $good['from_time'] = time();
            $goods[$key] = $good;
        }
        dump($goods);
    }

}

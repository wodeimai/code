<?php

namespace Go\Controller;

use Think\Controller;

/**
 * 采集控制器
 * 主要获取各个平台的聚合数据
 * safda
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
        \phpQuery::$documents = array();
    }

    public function detail()
    {
        $id = '3795151';
        $this->get_detail_content($id);
    }

    public function get_detail_content($id)
    {
        Vendor('phpQuery.phpQuery.phpQuery');
        $url = "http://guangdiu.com/detail.php?id=" . $id;
        \phpQuery::newDocumentFile($url);
        $html = pq('.detaildisplay');

        // 优质来源提示
        $ritems = pq($html)->find('.ritems')->text();
        $latesttime = pq($html)->find('.latesttime')->text();
        $ritems = str_replace($latesttime, '[latesttime]', $ritems);

        // 最先显示图片且为一张
        $dimage = pq($html)->find('.dimage img')->attr('src');
        $dimage_id = save_jpg($dimage, $id);

        // 最先显示图片且为两张
        $simgaheadleft = pq($html)->find('.simgaheadleft img')->attr('src');
        $simgaheadleft_id = save_jpg($simgaheadleft, $id);
        $simgaheadright = pq($html)->find('.simgaheadright img')->attr('src');
        $simgaheadright_id = save_jpg($simgaheadright, $id);
        $simgahead = $simgaheadleft_id . ',' . $simgaheadright_id;

        //
    }
}

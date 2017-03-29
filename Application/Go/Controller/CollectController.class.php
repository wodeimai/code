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
        $id = '3807486';
        $this->get_detail_content($id);
    }

    public function get_detail_content($id)
    {
        Vendor('phpQuery.phpQuery.phpQuery');
        $url = "http://guangdiu.com/detail.php?id=" . $id;
        \phpQuery::newDocumentFile($url);

        // div class=detaildisplay
        $html = pq('.detaildisplay');

        // 以下为div class=detaildisplay所有的子元素
        // link地址
        $dlink = pq($html)->find('.dtitlegotobuy')->attr('href');

        // class=dtitle 标题
        $dtitle = pq($html)->find('.dtitle a')->html();

        // class=ritems 优质来源提示
        $ritems = pq($html)->find('.ritems')->text();
        $latesttime = pq($html)->find('.latesttime')->text();
        $ritems = str_replace($latesttime, '[latesttime]', $ritems);

        // div class=dabstract 详细内容div
        $dabstract = pq($html)->find('#dabstract');

        foreach (pq($dabstract)->find('p') as $key => $value) {
            $p['p_' . ($key + 1)] = pq($value)->html();
        }

        foreach (pq($dabstract)->find('img') as $key => $value) {
            $img['pic_' . ($key + 1)] = pq($value)->attr('src');
        }

        if (empty($p)) {
            $p['p1'] = pq($dabstract)->html();
        }

        dump($p);
        echo "<br/>";
        dump($img);
        die;
    }

    public function get_reurl()
    {
        $url = "http://guangdiu.com/go.php?id=3829933";
        echo $this->curl_post($url);
    }

    // 获取网页跳转地址
    public function curl_post($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Fiddler");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 获取转向后的内容
        $data = curl_exec($ch);
        $Headers = curl_getinfo($ch);
        $url = substr($data, strpos($data, 'https'), strpos($data, '\';') - strpos($data, 'https'));
        return $url;
    }
}

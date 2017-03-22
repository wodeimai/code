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
        $id = '3801159';
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
            $p[$key] = pq($value)->html();
        }

        foreach (pq($dabstract)->find('img') as $key => $value) {
            $img[$key] = pq($value)->attr('src');
        }

        // $p1 = pq($dabstract)->find('p')->eq(0)->html();
        // $p2 = pq($dabstract)->find('p')->eq(1)->html();

        var_dump($p);
        var_dump($img);
        die;




        // 以下为div class=dabstract下所有的子元素
        // 最先显示图片且为一张(形式一)
        $dimage = pq($dabstract)->find('.dimage img')->attr('src');
        if(!empty($dimage)){
            $dimage_id = save_jpg($dimage, $id);
        }
        // 最先显示图片且为一张(形式二)
        $dt = pq($dabstract)->find('dt img')->attr('src');
        if(!empty($dt)){
            $dt_id = save_jpg($dt, $id);
        }
        // 最先显示图片且为一张(形式三)
        $editor_mod = pq($dabstract)->find('editor-mod img')->attr('src');
        if(!empty($editor_mod)){
            $editor_mod_id = save_jpg($editor_mod, $id);
        }

        // 最先显示图片且为两张
        $simgaheadleft = pq($dabstract)->find('.simgaheadleft img')->attr('src');
        $simgaheadleft_id = save_jpg($simgaheadleft, $id);
        $simgaheadright = pq($dabstract)->find('.simgaheadright img')->attr('src');
        $simgaheadright_id = save_jpg($simgaheadright, $id);
        $simgahead = $simgaheadleft_id . ',' . $simgaheadright_id;

        // div 文本内容(形式一)
        $hui_content_text = pq($dabstract)->find('.hui_content_text')->html();
        // div 文本内容(形式二)
        $huimabstract = pq($dabstract)->find('.dabstract')->html();
    }
}

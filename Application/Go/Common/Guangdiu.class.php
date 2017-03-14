<?php

namespace Go\Common;

use Go\Common\Cache as Cache;
use Go\Common\Curl;
use Vendor\simple_html_dom;

/**
 * 逛丢采集类
 */
class Guangdiu extends Curl
{

    const DOMAIN = 'http://guangdiu.com/';

    public function __construct()
    {
        $this->shd = new \Vendor\simple_html_dom();

    }

    /**
     * 采集
     */
    public function init()
    {
        $url = "http://guangdiu.com/cate.php?k=baby";
        $result = $this->check_new($url);
        return $result;
        //return true;
    }

    /**
     * 查询是否有新的数据,没有则返回信息，有则更新数据
     * @param  string  $url 要查询的网址
     * @param  integer $type 预留，要查询的类型,eg:奶粉、纸尿裤等
     * @return string
     */
    private function check_new($url, $type = null)
    {
        //检查缓存
        $guangdiuLastId = cache::getCacheLastId();

        if (empty($guangdiuLastId)) {
            log_error('获取逛丢最后id失败');
            return false;
        } else {
            //查询当前最新商品id
            $get_html = $this->getHtmlContent($url);
            $this->shd->load($get_html['result']);
            $detail_id = $this->shd->find("div.gooditem", 0)->find('a.goodname', 0)->href;
            $this->shd->clear(); //释放内存
            $last_id = preg_replace('/\D/', '', $detail_id);

            //检查是否有新数据
            if ($last_id <= $guangdiuLastId) {
                log_info("没有新数据更新");
                return true;
            } else {

                //更新缓存中的guangdiu_last_id
                //S('guangdiu_last_id',$guangdiu_last_id);
                //return $last_id . "===" . $guangdiuLastId;
                return $this->get_content($url, $guangdiuLastId);
            }

        }
    }

    /**
     * 采集信息
     * @param  integer $uid 用户ID
     * @param  integer $guangdiuLastId 缓存中最后更新过的商品id
     * @return boolean      ture-成功，false-失败
     */
    private function get_content($url, $guangdiuLastId)
    {
        $get_html = $this->getHtmlContent($url);
        $this->shd->load($get_html['result']);
        $guangdiu_go = 'go.php?id';

        foreach ($this->shd->find("div.gooditem") as $key => $item) {
            $target['herf'] = $item->find('a.goodname', 0)->href;
            $target['from_id'] = preg_replace('/\D/', '', $target['herf']);
            //只保存商品ID比缓存的商品ID大的数据
            log_info($target['from_id'] . '===========' . $guangdiuLastId);
            if ($target['from_id'] > $guangdiuLastId) {
                $article['title'] = $item->find('a.goodname', 0)->innertext;
                $article['desc'] = $item->find('a.abstractcontent', 0)->plaintext;
                $article['target'] = $item->find('a.rightmallname', 0)->plaintext;
                $article['from_id'] = $target['from_id'];
                $article['img'] = $item->find('img', 0)->src;
                $article['target_url'] = $item->find('a.innergototobuybtn', 0)->href;
                $article['source_name'] = $item->find('div.infofrom', 0)->plaintext;
                $article['source_time'] = $item->find('div.infotime', 0)->plaintext;

                //处理采集到的数组
                $list['form'] = "1"; //逛丢1
                $list['from_id'] = $article['from_id'];
                $list['title'] = trim($article['title']);
                $list['desc'] = str_replace('...&nbsp;完整阅读>', '', filter_space($article['desc']));
                $list['img'] = str_replace('?imageView2/2/w/224/h/224', '', $article['img']);
                $list['cate'] = '类别todo';
                $list['source_name'] = $article['source_name'];
                $list['source_time'] = get_guangdiu_from_time($article['source_time']);
                $list['target'] = $article['target'];
                $list['target_url'] = $article['target_url'];

                //如果是逛丢的京东,考拉海淘推广链接，则增加逛丢域名
                if (strstr($article['target_url'], $guangdiu_go)) {
                    $list['target_url'] = self::DOMAIN . $article['target_url'];
                }
                //保存图片
                //save_jpg($list['img'], $list['from_id']);
                $lists[$key] = $list;
            }

        }
        $this->shd->clear();
        return $lists;
    }

    /**
     * 获取网页资源
     * @param  $url  需要采集的网址
     * @return
     */
    private function getHtmlContent($url, $timeout = 10, $reload = true)
    {
        $form['result'] = $this->curl($url, $timeout, $reload, 'http://guangdiu.com');
        $form['header'] = $this->curlinfo;
        $form['error'] = $this->error;

        return $form;
    }

    /*
////////////////////////////////////////
$html = new \Vendor\simple_html_dom();
define('GD', 'http://guangdiu.com/');
$html->load_file(GD);
//$ret = $html->find('.gooditem withborder');

foreach ($html->find("div.gooditem") as $key => $item) {
$article[$key]['title'] = trim($item->find('h2.mallandname', 0)->plaintext);
$article[$key]['desc'] = str_replace('    ', '', trim($item->find('a.abstractcontent', 0)->plaintext));
$article[$key]['desc'] = str_replace(' ', '', $article[$key]['desc']);
$article[$key]['desc'] = str_replace('...&nbsp;完整阅读>', '', $article[$key]['desc']);
$article[$key]['mall'] = $item->find('a.rightmallname', 0)->plaintext;
$article[$key]['herf'] = $item->find('a.goodname', 0)->href;
$article[$key]['img'] = str_replace('?imageView2/2/w/224/h/224', '', $item->find('img', 0)->src);
//保存图片
//$content = file_get_contents($article[$key]['img']);
//file_put_contents('./Uploads/Picture/1/'.$key.'.jpg', $content);
}
$html->clear();
//dump($article);

foreach ($article as $key => $value) {
$url = GD . $value['herf'];
$url = $this->get_html_content($url);
$html->load($url);

$new[$key]['title'] = $value['title'];
$new[$key]['desc'] = $value['desc'];
$new[$key]['mall'] = $value['mall'];
$new[$key]['herf'] = $value['herf'];
$new[$key]['img'] = $value['img'];
$new[$key]['content'] = $html->find("div.dabstract", 0)->outertext;
$new[$key]['url'] = $html->find("a.dgotobutton", 0)->href;

$html->clear();
}

dump($new);*/

}

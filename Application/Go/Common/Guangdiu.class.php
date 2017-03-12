<?php

namespace Go\Common;

use Go\Common\Curl;
use Vendor\simple_html_dom;

/**
 * 逛丢采集类
 */
class Guangdiu extends Curl
{

    const DOMAIN = 'http://guangdiu.com/';
    protected $shd = null;

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
        $guangdiuLastId = S('guangdiu_last_id');

        if (empty($guangdiuLastId)) {
            log_error('获取逛丢最后id失败');
            //todo
            //读取数据库中逛丢最后的商品id，并且放入缓存中，然后重新执行
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
        foreach ($this->shd->find("div.gooditem") as $key => $item) {
            $target[$key]['herf'] = $item->find('a.goodname', 0)->href;
            $target[$key]['from_id'] = preg_replace('/\D/', '', $target[$key]['herf']);
            //只保存商品ID比缓存的商品ID大的数据
            if ($target[$key]['from_id'] > $guangdiuLastId) {
                $article[$key]['title'] = $item->find('a.goodname', 0)->innertext;
                $article[$key]['desc'] = $item->find('a.abstractcontent', 0)->plaintext;
                $article[$key]['site'] = $item->find('a.rightmallname', 0)->plaintext;
                $article[$key]['from_id'] = $target[$key]['from_id'];
                $article[$key]['img'] = $item->find('img', 0)->src;
                $article[$key]['target_url'] = $item->find('a.innergototobuybtn', 0)->href;
                $article[$key]['form_time'] = $item->find('div.infotime', 0)->plaintext;
            }
        }
        $this->shd->clear();

        //处理采集到的数组
        $guangdiu_go = 'go.php?id';
        foreach ($article as $key => $value) {
            $list[$key]['title'] = trim($value['title']);
            $value['desc'] = filter_space($value['desc']);
            $list[$key]['desc'] = str_replace('...&nbsp;完整阅读>', '', $value['desc']);
            $list[$key]['site'] = $value['site'];
            $list[$key]['from_id'] = $value['from_id'];
            $list[$key]['img'] = str_replace('?imageView2/2/w/224/h/224', '', $value['img']);
            $list[$key]['target_url'] = $value['target_url'];
            $list[$key]['form_time'] = get_guangdiu_from_time($value['form_time']);
            //如果是逛丢的京东,考拉海淘推广链接，则增加逛丢域名
            if (strstr($value['target_url'], $guangdiu_go)) {
                $list[$key]['target_url'] = self::DOMAIN . $value['target_url'];
            }
            //保存图片
            //save_jpg($value['img'], $value['from_id']);
        }
        //TODO  插入数据库
        //TODO  更新guangdiuLastId缓存值
        return $list;
        //dump($article);
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

    /**
     * 过滤字符串
     * @param  $url  需要采集的网址
     * @return
     */
    /*
 * 获取真实链接

private function getSearchHostUrl($url, $referer = 'http://guangdiu.com')
{
$results = array();
$results['form'] = $this->curl($url, 10, true, $referer, true);
return $results;
if ($this->curlinfo['http_code'] == 0) {
$results['url'] = '';
} else {
$results['url'] = $this->curlinfo['url'];
}

//用真实链接替换sogou link
$res = 'go.php?id';
if (strstr($results['url'], $res)) {
preg_match('/<META.*?URL=("|\')(.*?)("|\')/i', $results['form'], $matches);
$results['url'] = $matches['2'];
}

return $results;
}

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

<?php

namespace Go\Common;

use Go\Common\Curl;

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
     * 采集器初始化
     */
    public function init()
    {
        $url = "http://guangdiu.com/cate.php?k=baby";
        $result = $this->check_new($url);
        return $result;
    }

    /**
     * 查询是否有新的数据,没有则返回信息，有则更新数据
     * @param  string  $url 要查询的网址
     * @param  integer $type 预留，要查询的类型
     * @return string
     */
    private function check_new($url, $type = null)
    {
        //检查缓存
        $guangdiuLastId = S('guangdiu_last_id');

        if (empty($guangdiuLastId)) {
            log_error('获取逛丢最后id失败');
            return false;
        } else {
            //查询当前最新商品id
            $get_html = $this->getHtmlContent($url);
            $this->shd->load($get_html['result']);
            $detail_id = $this->shd->find("div.gooditem", 0)->find('a.goodname', 0)->href;
            $last_id = preg_replace('/\D/', '', $detail_id);

            //检查是否有新数据
            if ($last_id <= $guangdiuLastId) {
                log_info("没有新数据更新");
                return true;
            } else {

                //更新缓存中的guangdiu_last_id
                //S('guangdiu_last_id',$guangdiu_last_id);
                return $last_id . "===" . $guangdiuLastId;
            }

        }
    }

    /**
     * 采集信息
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    private function get_content($url)
    {

        $get_html = $this->getHtmlContent($url);

        $this->shd->load($get_html['result']);
        $guangdiu_go = 'go.php?id';
        foreach ($this->shd->find("div.gooditem") as $key => $item) {
            $article[$key]['title'] = trim($item->find('h2.mallandname', 0)->plaintext);
            $article[$key]['desc'] = str_replace('  ', '', trim($item->find('a.abstractcontent', 0)->plaintext));
            $article[$key]['desc'] = str_replace(' ', '', $article[$key]['desc']);
            $article[$key]['desc'] = str_replace('...&nbsp;完整阅读>', '', $article[$key]['desc']);
            $article[$key]['mall'] = $item->find('a.rightmallname', 0)->plaintext;
            $article[$key]['herf'] = $item->find('a.goodname', 0)->href;
            $article[$key]['img'] = str_replace('?imageView2/2/w/224/h/224', '', $item->find('img', 0)->src);
            $article[$key]['target_url'] = $item->find('a.innergototobuybtn', 0)->href;

            //用真实链接替换guangdiu link
            if (strstr($article[$key]['target_url'], $guangdiu_go)) {
                $article[$key]['target_url'] = self::DOMAIN . $article[$key]['target_url'];
            }

            //保存图片
            //$content = file_get_contents($article[$key]['img']);
            //file_put_contents('./Uploads/Picture/1/'.$key.'.jpg', $content);
        }
        $this->shd->clear();
        return $article;
        //dump($article);
    }

    /**
     * 过滤字符串
     * @param  $url  需要采集的网址
     * @return
     */
    /*
     * 获取真实链接
     */

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

<?php

namespace Go\Common;

use Go\Common\Curl;

/**
 * 逛丢采集类
 */
class Guangdiu extends Curl
{

    /**
     * 采集信息
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function get_content($url)
    {

        $goods_detail_html = $this->getHtmlContent($url);

        $html = new \Vendor\simple_html_dom();
        $html->load($goods_detail_html['result']);
        $guangdiu_go = 'go.php?id';
        foreach ($html->find("div.gooditem") as $key => $item) {
            $article[$key]['title'] = trim($item->find('h2.mallandname', 0)->plaintext);
            $article[$key]['desc'] = str_replace('  ', '', trim($item->find('a.abstractcontent', 0)->plaintext));
            $article[$key]['desc'] = str_replace(' ', '', $article[$key]['desc']);
            $article[$key]['desc'] = str_replace('...&nbsp;完整阅读>', '', $article[$key]['desc']);
            $article[$key]['mall'] = $item->find('a.rightmallname', 0)->plaintext;
            $article[$key]['herf'] = $item->find('a.goodname', 0)->href;
            $article[$key]['img'] = str_replace('?imageView2/2/w/224/h/224', '', $item->find('img', 0)->src);
            $article[$key]['target_url'] = $item->find('a.innergototobuybtn', 0)->href;

            //用真实链接替换guangdiu link
            // if (strstr($article[$key]['target_url'], $guangdiu_go)) {
            //     $urlss = $this->getSearchHostUrl("http://www.meidebi.com/g-1600203.html");
            //     //echo "http://guagndiu.com/" . $article[$key]['target_url'];exit;
            //     dump($urlss);exit;
            // }
            //if(如果链接中含有go。php 那么就替换){
            // $res = 'guangdiu.com/go.php?id';
            // if (strstr($article[$key]['target_url'], $res)) {
            //     preg_match('/<META.*?URL=("|\')(.*?)("|\')/i', $results['form'], $matches);
            //     $article[$key]['target_url'] = $matches['2'];
            // }
            // }

            //保存图片
            //$content = file_get_contents($article[$key]['img']);
            //file_put_contents('./Uploads/Picture/1/'.$key.'.jpg', $content);
        }
        $html->clear();
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

    public function getSearchHostUrl($url, $referer = 'http://guangdiu.com')
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

}

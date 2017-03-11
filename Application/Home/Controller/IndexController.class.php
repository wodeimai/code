<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController
{

    //系统首页
    public function index()
    {
        $html = new \Vendor\simple_html_dom();
        define('GD', 'http://guangdiu.com/');
        $html->load_file(GD);
        //$ret = $html->find('.gooditem withborder');

        foreach ($html->find("div.gooditem") as $key => $item) {
            $article[$key]['title'] = trim($item->find('h2.mallandname', 0)->plaintext);
            $article[$key]['desc'] = str_replace('	', '', trim($item->find('a.abstractcontent', 0)->plaintext));
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

        dump($new);
    }

    /*
     * 获取页面内容
     */
    private function get_html_content($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

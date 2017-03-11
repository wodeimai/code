<?php

namespace Go\Controller;

use Go\Common\Guangdiu;
use Think\Controller;

/**
 * 采集控制器
 * 主要获取各个平台的聚合数据
 */
class IndexController extends Controller
{

    protected function _initialize()
    {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

    }

    public function index()
    {
        $url = "http://guangdiu.com/cate.php?k=baby";

        $guangdiu = new Guangdiu(); //舆情查询
        $html = $guangdiu->get_content($url);
        log_debug("ceshia " . var_export($html, true));
        //dump($html);
        exit;
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

}

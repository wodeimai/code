<?php

namespace Go\Common;

use Go\Common\Cache as Cache;
Vendor('phpQuery.phpQuery.phpQuery');

/**
 * 逛丢采集类
 */
class Guangdiu
{

    const DOMAIN = 'http://guangdiu.com/';

    public function __construct()
    {
        
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
            /*
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
            }*/
            return $this->get_content($url, 1);

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
        $guangdiu_go = 'go.php?id';
        \phpQuery::newDocumentFile($url);
        $html = pq('.gooditem');
        foreach ($html as $key => $value) {
            $good['id']  = '1';//逛丢1
            $good['from_id'] = $article['from_id'];
            $good['title'] = trim(pq($value)->find('.iteminfoarea .goodname')->html());
            $good['desc'] = str_replace('...&nbsp;完整阅读>', '', filter_space(pq($value)->find('.abstractcontent')->text()));
            
            $good['site'] = pq($value)->find('.rightlinks .rightmallname')->text();
            $good['tongbu'] = pq($value)->find('.timeandfrom .infofrom')->text();
            $good['img'] = pq($value)->find('.imgandbtn img')->attr('src');
            $good['target_url'] = pq($value)->find('.rightlinks .innergototobuybtn')->attr('href');
            $good['from_id'] = substr($good['target_url'], strpos($good['target_url'], 'id=') + 3);
            $good['from_time'] = time();
            $goods[$key] = $good;
        }
        dump($goods);

        /*
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

        }*/
        //$this->shd->clear();
        return $goods;
    }
  
}

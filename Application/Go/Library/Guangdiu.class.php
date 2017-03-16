<?php

namespace Go\Library;

use Go\Library\Cache as Cache;

Vendor('phpQuery.phpQuery.phpQuery');

define('DOMAIN', 'http://guangdiu.com/');

/**
 * 逛丢采集类
 */
class Guangdiu
{
    public static $shd = null;

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
            return $this->get_content($url, 3779270);

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

            $good['from_id'] = preg_replace('/\D/', '', pq($value)->find('.mallandname .goodname')->attr('href'));

            //如果当前商品id大于缓存id
            if ($good['from_id'] > $guangdiuLastId) {
                $good['from'] = '1'; //逛丢1
                $good['title'] = trim(pq($value)->find('.iteminfoarea .goodname')->html());
                $good['desc'] = str_replace('...&nbsp;完整阅读>', '', filter_space(pq($value)->find('.abstractcontent')->text()));

                $good['cate'] = ""; //TODO分类id

                //目标站名称和链接，例如京东商城，天猫商城
                $good['target'] = pq($value)->find('.rightlinks .rightmallname')->text();
                $good['target_url'] = pq($value)->find('.rightlinks .innergototobuybtn')->attr('href');
                //如果是逛丢的京东,考拉海淘推广链接，则增加逛丢域名，TODO 后期替换成自己的推广链接
                if (strstr($good['target_url'], $guangdiu_go)) {
                    $good['target_url'] = DOMAIN . $good['target_url'];
                }

                //来源站名称和来源时间 例如 从什么值得买同步
                $good['source_name'] = pq($value)->find('.timeandfrom .infofrom')->text();
                $good['source_time'] = get_guangdiu_from_time(pq($value)->find('.timeandfrom .infotime')->text());

                $good['img'] = str_replace('?imageView2/2/w/224/h/224', '', pq($value)->find('.imgandbtn img')->attr('src'));
                $good['create_time'] = time();
                //保存图片
                //save_jpg($list['img'], $list['from_id']);
                $goods[$key] = $good;
            }

        }
        \phpQuery::$documents = array(); //释放内存
        //dump($goods);
        return $goods;
    }

}

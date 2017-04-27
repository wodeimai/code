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

    public function __construct()
    {

    }

    /**
     * 采集
     */
    public function init()
    {

        Cache::get_cate(); //商品分类放入缓存
        $cate_list = S('cate_list');

        foreach ($cate_list as $key => $value) {
            $url = "http://guangdiu.com/cate.php?p=1&k=" . $value['key'] . "";
            $result = $this->check_new($url, $value['key']);
            exit;
            sleep('2');
        }

        return $result;
        //return true;
    }

    /**
     * 查询是否有新的数据,没有则返回信息，有则更新数据
     * @param  string  $url 要查询的网址
     * @param  string  $cate_key 商品分类,eg:baby,travel等
     * @return string
     */
    private function check_new($url, $cate_key = null, $type = 1)
    {
        //检查缓存 1为逛丢
        $guangdiuLastId = Cache::getCacheLastId('1', $cate_key);
        if (empty($guangdiuLastId)) {
            //没有采集过
            log_debug('没有采集过');
            return $this->get_content($url, '1', $type, $cate_key);
        } else {
            //查询当前最新商品id
            \phpQuery::newDocumentFile($url);
            $html = pq('.gooditem');
            $last_id = preg_replace('/\D/', '', pq('.mallandname .goodname:eq(0)')->attr('href'));
            //检查是否有新数据
            if ($last_id <= $guangdiuLastId) {
                log_info("没有新数据更新");
                return true;
            } else {
                //更新缓存中的guangdiu_last_id
                log_debug('2222');
                $cache_name = $cate_key . "_last_id";
                S($cache_name, $guangdiu_last_id);
                return $this->get_content($url, $guangdiuLastId, $type, $cate_key);
            }

        }
    }

    /**
     * 采集信息
     * @param  integer $uid 用户ID
     * @param  integer $guangdiuLastId 缓存中最后更新过的商品id
     * @param  integer $type      国内为1  国外为2
     * @param  string  $cate_key  商品分类
     * @return boolean      ture-成功，false-失败
     */
    private function get_content($url, $guangdiuLastId, $type = '1', $cate_key)
    {
        $guangdiu_go = 'go.php?id';
        \phpQuery::newDocumentFile($url);
        $html[$cate_key] = pq('.gooditem');
        foreach ($html[$cate_key] as $key => $value) {

            $good['from_id'] = preg_replace('/\D/', '', pq($value)->find('.mallandname .goodname')->attr('href'));

            //如果当前商品id大于缓存id
            if ($good['from_id'] > $guangdiuLastId) {
                $good['from'] = '1'; //逛丢1
                $good['title'] = trim(pq($value)->find('.iteminfoarea .goodname')->html());
                $good['desc'] = str_replace('...&nbsp;完整阅读>', '', filter_space(pq($value)->find('.abstractcontent')->text()));
                $good['cate'] = $cate_key;
                //目标站名称和链接，例如京东商城，天猫商城
                $good['target'] = Cache::setMallName(pq($value)->find('.rightlinks .rightmallname')->text(), $type);
                $good['target_url'] = pq($value)->find('.rightlinks .innergototobuybtn')->attr('href');
                //如果是逛丢的京东,考拉海淘推广链接，则增加逛丢域名，TODO 后期替换成自己的推广链接
                if (strstr($good['target_url'], $guangdiu_go)) {
                    $good['target_url'] = $this->replace_guangdiu_url(DOMAIN . $good['target_url']);
                }

                //来源站名称和来源时间 例如 从什么值得买同步
                $good['source_name'] = pq($value)->find('.timeandfrom .infofrom')->text();
                $good['source_time'] = get_guangdiu_from_time(pq($value)->find('.timeandfrom .infotime')->text());

                $good['img'] = str_replace('?imageView2/2/w/224/h/224', '', pq($value)->find('.imgandbtn img')->attr('src'));
                $good['create_time'] = time();
                //保存图片
                $good['pic'] = save_jpg($good['img'], $good['from_id']);
                //$goods[$cate_key][$key] = $good;
                $goods = M('wdm_goods')->add($good);
            }

        }
        \phpQuery::$documents = array(); //释放内存
        //dump($goods);
        return $goods;
    }

    /**
     * 替换逛丢网址
     * @param  string     $url  原网址
     * @return string     替换后网址
     */
    private function replace_guangdiu_url($url)
    {

        //TODO 替换多麦中的   site_id  euid是否要替换还需要观察
        $new_url = curl_post($url);
        return $new_url;
    }
}

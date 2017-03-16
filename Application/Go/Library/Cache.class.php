<?php
namespace Go\Library;

/**
 * 缓存类
 */
class Cache
{

    public function __construct()
    {

    }

    /*
     * 获取最新的商品ID，并放入缓存中
     * $from 为采集来源站 1 为逛丢
     */
    public function getCacheLastId($from = '1')
    {
        $cache_last_id = S('last_id');
        if (!empty($cache_last_id)) {
            //缓存中如果存在则返回缓存值
            return S('last_id');
        } else {
            //反之则查找数据库赋值给缓存
            $last_id = M('wdm_goods')->where(array('from' => $from))->order('from_id desc')->getField('from_id');
            if (!empty($last_id)) {
                S('last_id', $last_id);
                return $last_id;
            } else {
                return '1'; //调用函数的地方判断了 empty返回值，所以此处为1不为0
            }
        }

    }

    /*
     * 获取最新的商品ID，并放入缓存中
     * $tyoe 为类别 1是国内 2是国外
     */
    public function getMallName($type = '1')
    {
        $mall_name = S('mall_name');
        if (!empty($mall_name)) {
            //缓存中如果存在则返回缓存值
            return S('mall_name');
        } else {
            //反之则查找数据库赋值给缓存
            $mall_name = M('wdm_mall')->where(array('type' => $type))->order('id desc')->select();
            if (!empty($mall_name)) {
                S('mall_name', $mall_name);
                return S('mall_name');
            } else {
                return array();
            }
        }
    }

    public function setMallName($name, $type)
    {
        $mall_name = self::getMallName($type);
        log_debug("缓存" . var_export($mall_name));
        if (in_array($name, $mall_name)) {
            //如果找到则返回对应的KEY
            log_debug('111');
            return array_keys($mall_name, $name);
        } else {
            $array = array(
                'type' => $type,
                'mall_name' => $name,
            );
            log_debug($array);exit;
            $id = M('wdm_mall')->add($array);
            if (!empty($id)) {
                $array['id'] = $id;
                array_push($mall_name, $array);
                S('mall_name', $mall_name);
                return $id;
            }
        }
    }
}

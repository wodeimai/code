<?php
namespace Go\Common;

/**
 * 缓存类
 */
class Cache
{

    public function __construct()
    {

    }

    public function getCacheLastId($from = '1')
    {
        $s_guangdiu_last_id = S('guangdiu_last_id');
        if (!empty($s_guangdiu_last_id)) {
            //缓存中如果存在则返回缓存值
            return S('guangdiu_last_id');
        } else {
            //反之则查找数据库赋值给缓存
            $last_id = M('wdm_goods')->where(array('from' => $from))->order('from_id desc')->getField('from_id');
            if (!empty($last_id)) {
                S('guangdiu_last_id', $last_id);
                return $last_id;
            } else {
                return '1';//调用函数的地方判断了 empty返回值，所以此处为1不为0
            }
        }

    }

    public function getMallName()
    {

    }

    public function setMallName()
    {

    }
}

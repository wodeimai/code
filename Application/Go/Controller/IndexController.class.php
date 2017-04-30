<?php

namespace Go\Controller;

use Go\Library\Guangdiu;
use Think\Controller;

/**
 * 采集控制器
 * 主要获取各个平台的聚合数据
 */
class IndexController extends Controller
{

    protected $guangdiu = null;

    public function __construct()
    {
        $this->guangdiu = new Guangdiu();
    }

    public function index()
    {
        echo "string";exit;
        $html = $this->guangdiu->init();
        dump($html);
        exit;
    }

    //TODO删除10天以上的数据
    public function del_ten_days_data()
    {

    }

    /**
     *  临时，删除指定名称的缓存
     *  cate_list    产品分类
     *  mall_name    商城名称
     */
    public function delcache()
    {
        $name = I('name');
        dump(S($name));
        S($name, null);
        dump(S($name));
    }

    public function dellastid()
    {
        S('sale_last_id', null);
        S('automobile_last_id', null);
        S('sport_last_id', null);
        S('makeup_last_id', null);
        S('travel_last_id', null);
        S('clothes_last_id', null);
        S('food_last_id', null);
        S('electrical_last_id', null);
        S('digital_last_id', null);
        S('daily_last_id', null);
        S('baby_last_id', null);
        S('stockup_last_id', null);
    }

}

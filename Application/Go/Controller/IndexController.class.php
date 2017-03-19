<?php

namespace Go\Controller;

use Go\Library\Guangdiu;
use Think\Controller;

/**
 * 采集控制器
 * 主要获取各个平台的聚合数据
 * 测试1
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

        $html = $this->guangdiu->init();
        dump($html);

        //$mallList = M('wdm_mall')->where(array('type' => '1'))->select();
        //dump($mallList);
        exit;
    }

}

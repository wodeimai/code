<?php

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
        echo "this is first page";
        log_debug('这是一个测试文件');
        $maxid = 10;
        $this->assign('maxid', $maxid);
        $this->display();
    }

    public function get_news()
    {
        // $model = M();
        // $maxid = I('maxid');
        // $map['id'] = array('gt', $maxid);
        // $news_cnt = $model->where($map)->count();
        $news_cnt = 1;
        $this->ajaxReturn(array('cnt' => $news_cnt));
    }
}

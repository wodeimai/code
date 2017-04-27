<?php
namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController
{

    public function _initialize()
    {
        parent::_initialize();

        $this->goods_model = D('wdm_goods');

        $this->set_array = $array;
    }

    //系统首页
    public function index()
    {
        //获取分类信息
        $category = S('cate_list');
        krsort($category);
        $this->assign('category', $category);

        /* 获取当前分类列表 */
        //分页数据
        $page || $page = I('p', 1, 'intval');
        $row = 20;
        $list_data['list_data'] = $this->goods_model->order('source_time desc')->page($page, $row)->select();

        //记录条数
        $count = $this->goods_model->count();
        if ($count > $row) {
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $list_data['_page'] = $page->show();
        }
        $mall_list = S('mall_name');
        dump($mall_list);
        foreach ($list_data['list_data'] as &$value) {
            $value['source_time'] = time_tran($value['source_time']);
            $value['target'] = '';
        }

        //dump($list_data);

        $this->assign($list_data);

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

<?php
namespace Home\Controller;

use Go\Library\Guangdiu as Go;

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
        $this->malls_model = D('wdm_mall');

    }

    //系统首页
    public function index()
    {
        //获取分类信息
        $category = S('cate_list');
        krsort($category);
        $this->assign('category', $category);

        //分页数据
        $page || $page = I('p', 1, 'intval');
        $row = 20;
        $list_data['list_data'] = $this->goods_model->order('source_time desc')->page($page, $row)->group('from_id')->select();

        //记录条数
        $count = $this->goods_model->count();
        if ($count > $row) {
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $list_data['_page'] = $page->show();
        }
        $mall_list = S('mall_name');
        foreach ($mall_list as $key => $value) {
            $new_mall_list[$value['id']] = $value['mall_name'];
        }
        foreach ($list_data['list_data'] as &$value) {
            $value['source_time'] = time_tran($value['source_time']);
            $value['target'] = $new_mall_list[$value['target']];
        }
        //dump($list_data);
        $this->assign($list_data);

        //获取商场数据
        $mall_list = $this->malls_model->where(array('type' => '1'))->order('sort desc')->limit('10')->select();
        $this->assign('mall_list', $mall_list);

        //获取今日商品数和预计总数
        $today_time = strtotime(date('Y-m-d', time()));
        $where_today['source_time'] = array('EGT', $today_time);
        $where_today['type'] = '0';
        $today_count = $this->goods_model->where($where_today)->count();
        $this->assign('today_count', $today_count);
        $total = total($today_count);
        $this->assign('total', $total);

        //10日总数
        $ten_days_number = strtotime(date('Y-m-d', strtotime('-10 days')));
        $where_total['source_time'] = array('EGT', $ten_days_number);
        $total_count = $this->goods_model->where($where_total)->count();
        $this->assign('total_count', $total_count);

        //当前类别
        $type = I('type') == 'haitao' ? '海淘' : '国内';
        $this->assign('type', $type);

        //当前最大ID
        $big_id = $this->goods_model->order('id desc')->limit(1)->getField('id');
        $big_id = '430';
        $this->assign('big_id', $big_id);

        $this->display();
    }

    //ajax获取更新数据
    public function get_news()
    {
        $maxid = I('maxid');
        log_info($maxid);
        $map['id'] = array('gt', $maxid);
        $news_cnt = $this->goods_model->where($map)->count();
        $this->ajaxReturn(array('cnt' => $news_cnt));
    }

    public function go2url()
    {
        $id = I('id');
        $url = $this->goods_model->where(array('id' => $id))->getField('target_url');
        header("location:$url");
        exit;
    }

    public function info()
    {
        $id = I('id');
        $info = $this->goods_model->where(array('id' => $id))->find();
        if (!$info['status']) {
            //检查来源站是哪儿，然后决定引用什么方法
            if ($info['from'] == '1') {
                $info['content'] = Go::get_detail_content($info['from_id']);
            }
            //更新数据表
            $info['status'] = 1;
            $this->goods_model->save($info);
        }

        //展现内容页
        $this->assign('info', $info);
        $this->display();
    }
}

<?php
namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	public function _initialize() {
		parent::_initialize();

		$this->goods_model = D('wdm_goods');

		$this->set_array = $array;
	}

	//系统首页
	public function index() {

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

		$this->assign($list_data);

		//获取商场数据

		//获取今日商品数和预计总数
		$today_time = strtotime(date('Y-m-d', time()));
		$where_today['source_time'] = array('EGT', $today_time);
		$where_today['type'] = '0';
		$today_count = $this->goods_model->where($where_today)->count();
		$this->assign('today_count', $today_count);
		$total = total($today_count);
		$this->assign('total', $total);

		//10日总数

		//当前类别
		$type = I('type') == 'haitao' ? '海淘' : '国内';
		$this->assign('type', $type);

		$this->display();
	}

	public function get_news() {
		// $model = M();
		// $maxid = I('maxid');
		// $map['id'] = array('gt', $maxid);
		// $news_cnt = $model->where($map)->count();
		$news_cnt = 1;
		$this->ajaxReturn(array('cnt' => $news_cnt));
	}
}

<?php

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
	public function index() {
		vendor('phpqrcode.phpqrcode');
		$object = new \QRcode();

		for ($i == '1'; $i <= '100'; $i++) {
			$png_qrcode = $i . '.png';
			$url = "http://www.abbottmama.com.cn/Knowledge/Detail/id/" . $i;
			$object->png($url, $png_qrcode, QR_ECLEVEL_L, 5);
		}
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

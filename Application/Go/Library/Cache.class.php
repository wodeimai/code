<?php
namespace Go\Library;

/**
 * 缓存处理类
 */
class Cache {

	public function __construct() {

	}

	/**
	 * 获取最新的商品ID，并放入缓存中
	 * @param  int   $from 为采集来源站 1 为逛丢
	 * @return int   商品id
	 */
	public function getCacheLastId($from = '1', $cate) {

		$name = $cate . '_last_id';
		$cache_last_id = S($name);
		if (!empty($cache_last_id)) {
			//缓存中如果存在则返回缓存值
			return S($name);
		} else {
			//反之则查找数据库赋值给缓存  from_id 为来源站id
			$last_id = M('wdm_goods')->where(array('from' => $from, 'cate' => $cate))->order('from_id desc')->getField('from_id');
			if (!empty($last_id)) {
				S($name, $last_id);
				return $last_id;
			} else {
				return false;
			}
		}

	}

	/**
	 * 获取商城名称，并放入缓存中
	 * @param  int   $type 为类别 1是国内 2是国外
	 * @return cache 把数组放入缓存
	 */
	public function getMallName($type = '1') {
		$mall_name = S('mall_name');
		if (!empty($mall_name)) {
			//缓存中如果存在则返回缓存值
			return S('mall_name');
		} else {
			//反之则查找数据库赋值给缓存
			$mall_name_list = M('wdm_mall')->where(array('type' => $type))->order('id asc')->select();
			if (!empty($mall_name_list)) {
				S('mall_name', $mall_name_list);
				return S('mall_name');
			} else {
				return array();
			}
		}
	}

	/**
	 * 获取商城名称，返回给程序商城id
	 * @param  string   $name  eg:京东
	 * @param  int      $type 为类别 1是国内 2是国外
	 * @return int      $id   商城id
	 */
	public function setMallName($name, $type) {

		$mall_name = self::getMallName($type);
		//log_debug("缓存" . var_export($mall_name));
		if (in_array($name, $mall_name)) {
			//如果找到则返回对应的KEY
			log_debug('=============' . $name);
			return array_keys($mall_name, $name);
		} else {
			$array = array(
				'type' => $type,
				'mall_name' => $name,
			);
			//查找是否有该名称的记录
			$info = M('wdm_mall')->where($array)->find();

			if (empty($info)) {
				$id = M('wdm_mall')->add($array);
				if (!empty($id)) {
					$array['id'] = $id;
					log_info($mall_name);
					array_push($mall_name, $array);
					log_debug($mall_name);
					S('mall_name', $mall_name);
					return $id;
				}
			} else {
				log_debug('查库得出id');
				return $info['id'];
			}
		}
	}

	/**
	 * 获取商品分类信息
	 * @param  int      $type 为类别 1是国内 2是国外
	 * @return int      $id   商城id
	 */
	public function get_cate($type = '1') {

		$cate_list = M('wdm_category')->where(array('type' => $type))->order('id desc')->Field('key,name')->select();
		if (!empty($cate_list)) {
			S('cate_list', $cate_list);
			return S('cate_list');
		} else {
			log_debug('获取商品分类信息失败');
			return array();
		}

	}

}

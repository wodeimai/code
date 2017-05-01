<?php
///////////////////////////////////////////////
// 项目扩展函数
// add by leo, 20170312
///////////////////////////////////////////////

/**
 * 计算每日预计总数  TODO
 * @param  int    $sum  当前数字
 * @return int    预计总数
 */
function total($sum)
{

/**
 * 沈志 11:23:17今日国内发布：465条预计：1715条
 * 沈志 11:38:07今日国内发布：511条预计：1731条
 * 沈志 13:26:12今日国内发布：663条预计：1705条
 * 沈志 15:39:06今日国内发布：1002条预计：1772条
 * 沈志 16:22:33今日国内发布：1099条预计：1773条
 * 沈志 22:25:33今日国内发布：1675条预计：1726条
 */

    $total = $sum * 3 - 365;
    return $total;
}

/**
 * 保存图片到本地
 * @param  string $img  图片地址
 * @param  int    $id   商品id
 * @return int    图片字节数
 */
function save_jpg($img, $id)
{
    $dir = './Uploads/Picture/' . date('Y-m-d') . '/';

    //检测文件夹是否建立
    if (!file_exists($dir)) {
        mkdir($dir, '0777', true);
    }
    $content = file_get_contents($img);

    $pic_name = time() . generate_str(6, 'num');
    file_put_contents($dir . $pic_name . '.jpg', $content);

    $dir_db = '/Uploads/Picture/' . date('Y-m-d') . '/';
    $data['path'] = $dir_db . $pic_name . '.jpg';
    $data['md5'] = md5_file($dir);
    $data['sha1'] = sha1_file($dir);
    $data['status'] = 1;
    $data['create_time'] = time();
    $pic_id = M('picture')->add($data);
    return $pic_id;
}

/**
 * 过滤各种空格
 * @param  string $string
 * @return string 返回过滤后的字符串
 */
function filter_space($string)
{
    $search = array(" ", "　", "\n", "\r", "\t");
    $replace = array("", "", "", "", "");
    return str_replace($search, $replace, $string);
}

/**
 * 格式化时间戳变为文字
 * @param  int    时间戳
 * @return string 文字时间 eg:5分钟前
 */
function time_tran($the_time)
{

    $now_time = time();
    $show_time = $the_time;
    $dur = $now_time - $show_time;

    if ($dur < 0) {
        return $the_time;
    } elseif ($dur < 60) {
        return $dur . '秒前';
    } elseif ($dur < 3600) {
        return floor($dur / 60) . '分钟前';
    } elseif ($dur < 86400) {
        return floor($dur / 3600) . '小时前';
    } elseif ($dur < 259200) {
        return floor($dur / 86400) . '天前';
    } else {
        return date("Y-m-d", $the_time);
    }
}

/**
 * 格式化从逛丢获取的来源时间
 * @param  string $string  eg:5分钟
 * @return int 返回当前时间减去int的时间戳
 */
function get_guangdiu_from_time($string)
{
    $minites = "分钟";
    $hours = "小时";
    $yesterday = "昨天";
    $tdby = "前天";

    if (strstr($string, $minites)) {
        $int = preg_replace('/\D/', '', $string);
        $return_time = time() - $int * 60;
    } elseif (strstr($string, $hours)) {
        $int = preg_replace('/\D/', '', $string);
        $return_time = time() - $int * 60 * 60;
    } elseif (strstr($string, $yesterday)) {
        $times = str_replace('昨天', '', $string);
        $yesterday_day = strtotime("-1 day");
        $string2time = strtotime(date("Y-m-d", $yesterday_day) . " " . $times);
        $return_time = $string2time;
    } elseif (strstr($string, $tdby)) {
        $times = str_replace('前天', '', $string);
        $tdby_day = strtotime("-2 day");
        $string2time = strtotime(date("Y-m-d", $tdby_day) . " " . $times);
        $return_time = $string2time;
    }
    return $return_time;
}

/**
 * 获取网页跳转地址
 * @param  string $string  网址
 * @return string 返回当前真实网址
 */
function curl_post($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Fiddler");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 获取转向后的内容
    $data = curl_exec($ch);
    $Headers = curl_getinfo($ch);
    $url = substr($data, strpos($data, 'https'), strpos($data, '\';') - strpos($data, 'https'));
    return $url;
}

function generate_str($length = 8, $type = 'str')
{
    $num = '0123456789';
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($type == 'all') {
        $chars = $chars . $num;
    }
    if ($type == 'num') {
        $chars = $num;
    }

    $str = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
}

<?php
///////////////////////////////////////////////
// 项目扩展函数
// add by leo, 20170312
///////////////////////////////////////////////

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
        mkdir($dir);
    }
    $content = file_get_contents($img);
    return file_put_contents($dir . $id . '.jpg', $content);
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
 * 从逛丢格式化来源时间
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

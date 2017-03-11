<?php

namespace Go\Common;

class Curl
{

    protected $error;
    protected $curlinfo;
    public $headerStat = false;
    public $user_agent_pc = array(
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11', //淘宝浏览器2.0 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50', //safari 5.1 – Windows
        'User-Agent:Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50', //safari 5.1 – MAC
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER', //猎豹浏览器2.0.10.3198 急速模式on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER) ', //猎豹浏览器2.0.10.3198 兼容模式on Windows 7 x64：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E; LBBROWSER)', //猎豹浏览器2.0.10.3198 兼容模式on Windows XP x86 IE6：
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 LBBROWSER', //猎豹浏览器1.5.9.2888 急速模式on Windows 7 x64：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E) ', //猎豹浏览器1.5.9.2888 兼容模式 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400) ', //QQ浏览器7.0 on Windows 7 x64 IE9：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E) ', //QQ浏览器7.0 on Windows XP x86 IE6：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; 360SE) ', //360安全浏览器5.0自带IE8内核版 on Windows XP x86 IE6：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E) ', //360安全浏览器5.0 on Windows XP x86 IE6：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E) ', //360安全浏览器5.0 on Windows 7 x64 IE9：
        'User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1', //360急速浏览器6.0 急速模式 on Windows XP x86：
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1', //360急速浏览器6.0 急速模式 on Windows 7 x64：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E) ', //360急速浏览器6.0 兼容模式 on Windows XP x86 IE6：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E) ', //360急速浏览器6.0 兼容模式 on Windows 7 x64 IE9：
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E) ', //360急速浏览器6.0 IE9/IE10模式 on Windows 7 x64 IE9：
        'User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 SE 2.X MetaSr 1.0', //搜狗浏览器4.0 高速模式 on Windows XP x86：
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0) ', //搜狗浏览器4.0 兼容模式 on Windows XP x86 IE6：
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:16.0) Gecko/20121026 Firefox/16.0', //Waterfox 16.0 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:2.0b13pre) Gecko/20110307 Firefox/4.0b13pre', //Firefox x64 4.0b13pre on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:16.0) Gecko/20100101 Firefox/16.0', //Firefox x64 on Ubuntu 12.04.1 x64：
        'User-Agent:Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15', //Firefox x86 3.6.15 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11', //Chrome x64 on Ubuntu 12.04.1 x64：
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11', //Chrome x86 23.0.1271.64 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16', //Chrome x86 10.0.648.133 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)', //IE9 x64 9.0.8112.16421 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', //IE9 x86 9.0.8112.16421 on Windows 7 x64：
        'User-Agent:Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10', //Firefox x64 3.6.10 on Ubuntu 10.10 x64：
        'User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.12) Gecko/20080219 Firefox/2.0.0.12 Navigator/9.0.0.6', //Navigator 浏览器
        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;', //IE 9.0
        'User-Agent:Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)', //IE 8.0
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', //IE 7.0
        'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)', //IE 6.0
        'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', //Firefox 4.0.1 – MAC
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', //Firefox 4.0.1 – Windows
        'User-Agent:Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11', //Opera 11.11 – MAC
        'User-Agent:Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11', //Opera 11.11 – Windows
        'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', //Chrome 17.0 – MAC
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)', //傲游（Maxthon）
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)', //腾讯TT
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)', //世界之窗（The World） 2.x
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)', //世界之窗（The World） 3.x
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)', //搜狗浏览器 1.x
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)', //360浏览器
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)', //Avant
        'User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)', //Green Browser
        'User-Agent:Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.70 Safari/534.6 TouchPad/1.0', //WebOS HP Touchpad
    );

    public function curl($url = 'http://m.baidu.com', $timeout = 20, $isReload = true, $referer = '', $is_redirect = true, $head = array(), $https = true)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curlHandle, CURLOPT_HEADER, $this->headerStat);
        curl_setopt($curlHandle, CURLOPT_NOBODY, false);
        curl_setopt($curlHandle, CURLOPT_VERBOSE, true);
        if ($https) {
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($head) {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $head);
        } else {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->head);
        }

        if (isset($this->cookieFile)) {
            curl_setopt($curlHandle, CURLOPT_COOKIEJAR, $this->cookieFile);
            curl_setopt($curlHandle, CURLOPT_COOKIEFILE, $this->cookieFile);
        }
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 2);
        curl_setopt($curlHandle, CURLOPT_ENCODING, "gzip");
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_AUTOREFERER, true);
        if ($referer) {
            curl_setopt($curlHandle, CURLOPT_REFERER, $referer);
        }

        if ($is_redirect) {
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        }
        // 302 redirect
        if (!$this->ip) { //$this->redis->setCatNum('daili');
            curl_setopt($curlHandle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($curlHandle, CURLOPT_PROXY, $this->ip[0]); //代理服务器地址
            curl_setopt($curlHandle, CURLOPT_PROXYPORT, $this->ip[1]); //代理服务器端口
            curl_setopt($curlHandle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        $result = curl_exec($curlHandle);
        $this->curlinfo = curl_getinfo($curlHandle);
        $this->error = curl_error($curlHandle);

        if ($isReload && $this->curlinfo['http_code'] == 0) {
            return $this->curl($url, $timeout, false);
        }

        curl_close($curlHandle);
        return $result;
    }

    //转码
    public function is_utf8($str)
    {
        $e = mb_detect_encoding($str, array('UTF-8', 'GBK'));
        if ($e != 'UTF-8') {
            $str = array_iconv($str);
        }
        return $str;
    }

    /*
     * 清除Html标签
     */

    public function cleanHtml($str, $is_title = true)
    {

        $str = preg_replace(array('#<script[^>]*?>.*?</script>#si', '#<style[^>]*?>.*?</style>#si', '#\s#i'), array(''), $str);
        $info = array();
        if ($is_title) {
            preg_match('#<title>(.*?)<\/title>#is', $str, $title);
            if ($title[1]) {
                $info['title'] = $title[1];
            } else {
                $info['title'] = '';
            }

        }
        $info['result'] = strip_tags($str);
        $arr = explode($info['title'], $info['result']);
        array_shift($arr);
        $info['result'] = implode($info['title'], $arr);
        return $info;
    }

}

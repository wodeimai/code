<?php
////////////////////////////////////////////////////////
// 为PHPUNIT测试案例准备环境变量
// 请在测试案例的文件头包含本文件即可
// Akita Studio, 201511
////////////////////////////////////////////////////////

//////////////////////////////////////////
/// from index.php

define('__ROOT__', __DIR__);
/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);
define('No_CACHE_RUNTIME', true);

/**
 * 官方远程同步服务器地址
 * 应用于后台应用商店、在线升级等功能
 */
define('REMOTE_BASE_URL', 'http://www.weiphp.cn');

// 网站根路径设置
define('SITE_PATH', dirname(__FILE__));
/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
//网站根目录地址
define('UPLOAD_PATH', __ROOT__ . '/Uploads/');
define('APP_PATH', __ROOT__ . '/Application/');

////////////////////////////////////////////////////////
/// from ThinkPHP.php

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(true);
// 记录内存初始使用
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
if (MEMORY_LIMIT_ON) {
    $GLOBALS['_startUseMems'] = memory_get_usage();
}

// 版本信息
const THINK_VERSION = '3.2.0';

// URL 模式定义
const URL_COMMON = 0; //普通模式
const URL_PATHINFO = 1; //PATHINFO模式
const URL_REWRITE = 2; //REWRITE模式
const URL_COMPAT = 3; // 兼容模式

// 类文件后缀
const EXT = '.class.php';

// 系统常量定义
defined('THINK_PATH') or define('THINK_PATH', __DIR__ . '/ThinkPHP/');
defined('APP_STATUS') or define('APP_STATUS', ''); // 应用状态 加载对应的配置文件

if (function_exists('saeAutoLoader')) {
// 自动识别SAE环境
    defined('APP_MODE') or define('APP_MODE', 'sae');
    defined('STORAGE_TYPE') or define('STORAGE_TYPE', 'Sae');
} else {
    defined('APP_MODE') or define('APP_MODE', 'common'); // 应用模式 默认为普通模式
    defined('STORAGE_TYPE') or define('STORAGE_TYPE', 'File'); // 存储类型 默认为File
}

defined('RUNTIME_PATH') or define('RUNTIME_PATH', __ROOT__ . '/Runtime/'); // 系统运行时目录
defined('LIB_PATH') or define('LIB_PATH', realpath(THINK_PATH . 'Library') . '/'); // 系统核心类库目录
defined('CORE_PATH') or define('CORE_PATH', LIB_PATH . 'Think/'); // Think类库目录
defined('BEHAVIOR_PATH') or define('BEHAVIOR_PATH', LIB_PATH . 'Behavior/'); // 行为类库目录
defined('EXTEND_PATH') or define('EXTEND_PATH', THINK_PATH . 'Extend/'); // 系统扩展目录
defined('VENDOR_PATH') or define('VENDOR_PATH', LIB_PATH . 'Vendor/'); // 第三方类库目录
defined('COMMON_PATH') or define('COMMON_PATH', APP_PATH . 'Common/'); // 应用公共目录
defined('CONF_PATH') or define('CONF_PATH', COMMON_PATH . 'Conf/'); // 应用配置目录
defined('LANG_PATH') or define('LANG_PATH', COMMON_PATH . 'Lang/'); // 应用语言目录
defined('HTML_PATH') or define('HTML_PATH', APP_PATH . 'Html/'); // 应用静态目录
defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'Logs/'); // 应用日志目录
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'Temp/'); // 应用缓存目录
defined('DATA_PATH') or define('DATA_PATH', RUNTIME_PATH . 'Data/'); // 应用数据目录
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'Cache/'); // 应用模板缓存目录

// 系统信息
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    ini_set('magic_quotes_runtime', 0);
    define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc() ? true : false);
} else {
    define('MAGIC_QUOTES_GPC', false);
}
define('IS_CGI', (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) ? 1 : 0);
define('IS_WIN', strstr(PHP_OS, 'WIN') ? 1 : 0);
define('IS_CLI', PHP_SAPI == 'cli' ? 1 : 0);

if (!IS_CLI) {
    // 当前文件名
    if (!defined('_PHP_FILE_')) {
        if (IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp = explode('.php', $_SERVER['PHP_SELF']);
            define('_PHP_FILE_', rtrim(str_replace($_SERVER['HTTP_HOST'], '', $_temp[0] . '.php'), '/'));
        } else {
            define('_PHP_FILE_', rtrim($_SERVER['SCRIPT_NAME'], '/'));
        }
    }
    if (!defined('__ROOT__')) {
        $_root = rtrim(dirname(_PHP_FILE_), '/');
        define('__ROOT__', (($_root == '/' || $_root == '\\') ? '' : $_root));
    }
}
define('SITE_DOMAIN', 'test.akitastudio.cn');
define('SITE_URL', 'http://' . SITE_DOMAIN);
/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @return mixed
 */
function C($name = null, $value = null, $default = null)
{
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value)) {
                return isset($_config[$name]) ? $_config[$name] : $default;
            }

            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0] = strtoupper($name[0]);
        if (is_null($value)) {
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        }

        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)) {
        $_config = array_merge($_config, array_change_key_case($name, CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}
// 加载核心Think类
require CORE_PATH . 'Think' . EXT;

spl_autoload_register('Think\Think::autoload');

/////////////////////////////////////////////////
/// for Thinkphp::start

// 读取应用模式
$mode = include is_file(CONF_PATH . 'core.php') ? CONF_PATH . 'core.php' : THINK_PATH . 'Conf/Mode/' . APP_MODE . '.php';

// 加载核心文件
foreach ($mode['core'] as $file) {
    if (is_file($file)) {
        include $file;
        if (!APP_DEBUG) {
            $content .= compile($file);
        }

    }
}

// 加载应用模式配置文件
foreach ($mode['config'] as $key => $file) {
    is_numeric($key) ? C(include $file) : C($key, include $file);
}

// 读取当前应用模式对应的配置文件
if ('common' != APP_MODE && is_file(CONF_PATH . 'config_' . APP_MODE . '.php')) {
    C(include CONF_PATH . 'config_' . APP_MODE . '.php');
}

// 调试模式加载系统默认的配置文件
C(include THINK_PATH . 'Conf/debug.php');
// 读取应用调试配置文件
if (is_file(CONF_PATH . 'debug.php')) {
    C(include CONF_PATH . 'debug.php');
}

C('AUTOLOAD_NAMESPACE', array('Addons' => __ROOT__ . '/Addons'));
C('DB_TYPE', 'Mysqli');

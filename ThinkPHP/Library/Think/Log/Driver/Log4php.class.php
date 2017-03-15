<?php
/**
 * 日志驱动：log4net
 * leo  20170311
 */

namespace Think\Log\Driver;

class Log4php
{
    protected $config = array(
    );

    // 实例化并传入参数
    public function __construct($config = array())
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log, $destination = '')
    {
        log_debug($log); //由于这个层级拿不到日志级别，暂时全部写为debug，供调试用
    }
}

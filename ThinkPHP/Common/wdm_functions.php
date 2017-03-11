<?php
///////////////////////////////////////////////
// 基础扩展函数
// add by leo, 20170310
///////////////////////////////////////////////
$__logger = null;
function __get_logger()
{
    global $__logger;
    if ($__logger) {
        return $__logger;
    }

    vendor('log4php.Logger');
    Logger::configure(SITE_PATH . '/log4php.properties');
    $__logger = Logger::getLogger("main");
    return $__logger;
}

function log_debug($message)
{
    $logger = __get_logger();
    $logger->debug($message);
}

function log_warn($message)
{
    $logger = __get_logger();
    $logger->warn($message);
}

function log_error($message)
{
    $logger = __get_logger();
    $logger->error($message);
}

function log_info($message)
{
    $logger = __get_logger();
    $logger->info($message);
}
//////////////////////////////////////////////

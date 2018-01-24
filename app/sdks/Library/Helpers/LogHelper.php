<?php

namespace App\Sdks\Library\Helpers;

use \SeasLog as Log;

/**
 * log帮助类库
 *
 * @author dusong<1264735045@qq.com>
 * @link https://github.com/Neeke/SeasLog
 */
class LogHelper
{
    // debug信息、细粒度信息事件
    const DEBUG = 1;

    // 重要事件、强调应用程序的运行过程
    const INFO = 2;

    // 一般重要性事件、执行过程中较INFO级别更为重要的信息
    //const NOTICE        = 3;

    // 出现了非错误性的异常信息、潜在异常信息、需要关注并且需要修复
    const WARNING = 4;

    // 运行时出现的错误、不必要立即进行修复、不影响整个逻辑的运行、需要记录并做检测
    const ERROR = 5;

    // 紧急情况、需要立刻进行修复、程序组件不可用
    const CRITICAL = 6;

    // 必级立即采取行动的紧急事件、需要立即通知相关人员紧急修复
    //const ALERT         = 7;

    // 系统不可用
    //const EMERGENCY     = 8;


    /**
     * 设置log根目录
     *
     * @param  string $base_path
     * @return bool
     */
    public static function setBasePath($base_path)
    {
        return Log::setBasePath($base_path);
    }

    /**
     * 获取log根目录
     *
     * @return string
     */
    public static function getBasePath()
    {
        return Log::getBasePath();
    }

    /**
     * 设置本次请求标识
     *
     * @param  string $request_id
     * @return bool
     */
    public static function setRequestId($request_id)
    {
        return Log::getRequestID($request_id);
    }

    /**
     * 获取本次请求标识
     *
     * @return string
     */
    public static function getRequestId()
    {
        return Log::getRequestID();
    }

    /**
     * 设置模块目录
     *
     * @param  string $module
     * @return bool
     */
    protected static function setLogger($module)
    {
        return Log::setLogger($module);
    }

    /**
     * 获取最后一次设置的模块目录
     *
     * @return string
     */
    public static function getLastLogger()
    {
        return Log::getLastLogger();
    }

    /**
     * 获得当前日志buffer中的内容
     *
     * @return array
     */
    public static function getBuffer()
    {
        return Log::getBuffer();
    }

    /**
     * 将buffer中的日志立刻刷到硬盘
     *
     * @return bool
     */
    public static function flushBuffer()
    {
        return Log::flushBuffer();
    }

    /**
     * 记录debug日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    public static function debug($type, $mixed, $is_common = true)
    {
        self::log(self::DEBUG, $type, $mixed, $is_common);
    }

    /**
     * 记录info日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    public static function info($type, $mixed, $is_common = true)
    {
        self::log(self::INFO, $type, $mixed, $is_common);
    }

    /**
     * 记录notice日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    /*public static function notice($type, $mixed, $is_common = true)
    {
        self::log(self::NOTICE, $type, $mixed, $is_common);
    }*/

    /**
     * 记录warning日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    public static function warning($type, $mixed, $is_common = true)
    {
        self::log(self::WARNING, $type, $mixed, $is_common);
    }

    /**
     * 记录error日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    public static function error($type, $mixed, $is_common = true)
    {
        self::log(self::ERROR, $type, $mixed, $is_common);
    }

    /**
     * 记录critical日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    public static function critical($type, $mixed, $is_common = true)
    {
        self::log(self::CRITICAL, $type, $mixed, $is_common);
    }

    /**
     * 记录alert日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    /*public static function alert($type, $mixed, $is_common = true)
    {
        self::log(self::ALERT, $type, $mixed, $is_common);
    }*/

    /**
     * 记录emergency日志
     *
     * @param  string $type
     * @param  mixed  $mixed
     * @param  bool   $is_common
     * @return void
     */
    /*public static function emergency($type, $mixed, $is_common = true)
    {
        self::log(self::EMERGENCY, $type, $mixed, $is_common);
    }*/


    /**
     * 获取公用信息
     *
     * @return string
     */
    public static function getCommonInfo()
    {
        // 获取服务器地址
        $server_addr = '';
        if (!empty($_SERVER['SERVER_ADDR'])) {
            $server_addr = $_SERVER['SERVER_ADDR'];
        } else {
            if (!empty($_SERVER['HOSTNAME'])) {
                $server_addr = $_SERVER['HOSTNAME'];
            }
        }

        // 组合数据
        $append_msg = [
            '@timestamp'     => date(\DateTime::ISO8601),
            'request_guid'   => self::getRequestId(),
            'server_addr'    => $server_addr,
            'remote_addr'    => CommonHelper::getClientIp(),
            'request_method' => empty($_SERVER['REQUEST_METHOD']) ? '' : $_SERVER['REQUEST_METHOD'],
            'domain'         => empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST'],
            'referer'        => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
            'agent'          => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
            'request'        => empty($_REQUEST) ? '' : http_build_query($_REQUEST),
            'uri'            => empty($_SERVER['REQUEST_URI']) ? '' : $_SERVER['REQUEST_URI'],
        ];

        return $append_msg;
    }

    /**
     * 通用日志方法
     *
     * @param  int     $level
     * @param  string  $type
     * @param  mixed   $mixed_msg
     * @param  bool    $is_common
     * @return void
     */
    protected static function log($level, $type, $mixed_msg, $is_common)
    {
        $module = [
            self::DEBUG    => 'debug',
            self::INFO     => 'info',
            self::WARNING  => 'warning',
            self::ERROR    => 'error',
            self::CRITICAL => 'critical',
        ][$level];

        // 定义错误信息处理函数
        $msg_func = function ($mixed) {
            if ($mixed instanceof \Exception) {
                // 记录错误信息
                $msg = "Error Code: " . $mixed->getCode() . "\n";
                $msg .= $mixed->getMessage() . "\n";
                $msg .= $mixed->getTraceAsString();
            } else if (!is_string($mixed)) {
                // 格式转换
                $msg = var_export($mixed, true);
            } else {
                $msg = $mixed;
            }
            return $msg;
        };

        // 公用数据
        $common_data = $is_common ? self::getCommonInfo() : [];
        $common_data = array_merge($common_data, ['msg_type' => $type]);

        // 合并数据
        if (is_array($mixed_msg)) {
            $msg_data = array_merge($common_data, $mixed_msg);
        } else {
            $msg_data = array_merge($common_data, ['message' => $msg_func($mixed_msg)]);
        }
        $msg = json_encode($msg_data);

        // 设置模块目录
        self::setLogger($module);

        // 写入日志
        Log::$module($msg);
    }

}

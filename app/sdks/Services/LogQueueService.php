<?php

namespace App\Sdks\Services;

use App\Sdks\Library\Helpers\CommonHelper;
use App\Sdks\Library\Helpers\IdHelper;
use App\Sdks\Library\Helpers\LogHelper;
use App\Sdks\Models\Logic\LogLogic;
use App\Sdks\Models\Logic\UserLogic;

/**
 * 日志队列服务
 *
 * @author dusong<1264735045@qq.com>
 */
class LogQueueService extends BaseService
{
    /**
     * 获取日志逻辑层
     *
     * @return LogLogic
     */
    protected static function getLogLogic()
    {
        return new LogLogic();
    }

    /**
     * 获取日志级别数据
     *
     * @return array
     */
    public static function getLogLevel(): array
    {
        return static::getLogLogic()->getLogLevel();
    }

    /**
     * 同步应用日志
     *
     * @param  array $data
     * @return bool
     */
    public static function syncAppLog(array $data)
    {
        $log_level = $data['log_level'];
        $log_type  = $data['log_type'];
        $log_data  = CommonHelper::jsonDecode($data['log_data']);
        if (!empty(!empty($data['common']))) {
            $log_data  = CommonHelper::arrayMerge($data['common'], $log_data);
        }
        LogHelper::$log_level($log_type, $log_data);

        return true;
    }

}

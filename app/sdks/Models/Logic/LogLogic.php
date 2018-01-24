<?php

namespace App\Sdks\Models\Logic;

use App\Sdks\Library\Constants\Queue\QueueTask;
use App\Sdks\Library\Error\ErrorHandle;
use App\Sdks\Library\Error\handlers\Err;
use App\Sdks\Library\Error\Settings\CoreLogic;
use App\Sdks\Library\Helpers\CommonHelper;
use App\Sdks\Library\Helpers\IdHelper;
use App\Sdks\Library\Helpers\LogHelper;
use App\Sdks\Models\Dao\UserDao;
use App\Sdks\Services\QueueService;

/**
 * 日志逻辑
 *
 * @author dusong<1264735045@qq.com>
 */
class LogLogic extends BaseLogic
{
    /**
     * 获取日志公用参数
     *
     * @return array
     */
    public static function getCommonLog(): array
    {
        return LogHelper::getCommonInfo();
    }

    /**
     * 获取最终日志数据
     *
     * @param  array  $log
     * @param  bool   $is_common
     * @return array
     */
    protected static function getFinalLog(array $log, bool $is_common = true): array
    {
        $log_data = [];
        if ($is_common) {
            $log_data['common'] = static::getCommonLog();
        }
        $log_data = CommonHelper::arrayMerge($log_data, $log);

        return $log_data;
    }

    /**
     * 获取日志级别
     *
     * @return array
     */
    public function getLogLevel() : array
    {
        $obj       = new \ReflectionClass('App\Sdks\Library\Constants\LogLevel');
        $log_level = $obj->getConstants();

        return $log_level;
    }

    /**
     * 保存应用日志
     *
     * @param  array  $params
     * @return bool
     */
    public function svaeAppLog(array $params)
    {
        $log_data = static::getFinalLog($params);
        $res = QueueService::send(QueueTask::APP_LOG, $log_data);

        return $res;
    }
}

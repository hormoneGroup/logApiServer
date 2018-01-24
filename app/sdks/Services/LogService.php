<?php

namespace App\Sdks\Services;

use App\Sdks\Library\Helpers\CommonHelper;
use App\Sdks\Library\Helpers\IdHelper;
use App\Sdks\Models\Logic\LogLogic;

/**
 * 日志服务
 *
 * @author dusong<1264735045@qq.com>
 */
class LogService extends BaseService
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
     * 保存应用日志
     *
     * @param  array  $params
     * @return bool
     */
    public static function svaeAppLog(array $params): bool
    {
        return static::getLogLogic()->svaeAppLog($params);
    }
}

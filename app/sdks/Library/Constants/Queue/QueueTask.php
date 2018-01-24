<?php

namespace App\Sdks\Library\Constants\Queue;

/**
 * 队列配置
 *
 * @author dusong<1264735045@qq.com>
 */
class QueueTask
{
    // 应用日志
    const APP_LOG = 'app_log';

    public static $SETTINGS = [
        self::APP_LOG => [
            'tube'      => QueueTube::ASYNC_APP_LOG,
            'exec_func' => 'App\Sdks\Services\LogQueueService::syncAppLog',
        ],
    ];
}

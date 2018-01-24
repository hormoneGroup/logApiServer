<?php

namespace App\Tasks\Backend;

use App\Sdks\Library\Constants\Queue\QueueTube;

/**
 * 日志task
 *
 * @author dusong<1264735045@qq.com>
 */
class LogTask extends BaseTask
{
    /**
     * 同步应用日志队列
     */
    public function asyncAppLogAction()
    {
        self::runBeanstalkTask(QueueTube::ASYNC_APP_LOG);
    }
}

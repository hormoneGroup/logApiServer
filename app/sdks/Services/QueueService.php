<?php

namespace App\Sdks\Services;

use App\Sdks\Library\Constants\Queue\QueueTask;
use App\Sdks\Library\Constants\Queue\QueueTaskConfig;
use App\Sdks\Library\Helpers\CommonHelper;
use App\Sdks\Library\Helpers\IdHelper;
use App\Sdks\Library\Helpers\LogHelper;
use Pheanstalk\PheanstalkInterface;

/**
 * 队列服务
 *
 * @author dusong<1264735045@qq.com>
 */
class QueueService extends BaseService
{
    /**
     * 放入队列，异步同步数据
     *
     * @param int    $task_type    任务所属管道
     * @param array  $data         传递给任务消费者的参数
     * @param int    $delay        延时任务 单位:秒s
     * @param int    $priority     任务优先级
     *
     * @return bool
     */
    public static function send($task_type, $data, $delay = 0, $priority = PheanstalkInterface::DEFAULT_PRIORITY)
    {
        try {
            $queue = static::getShared('beanstalk');
            $queue_data = [
                'task_type' => $task_type,
                'data'      => $data,
                'add_time'  => time(),
            ];
            $job_id = $queue
                ->useTube(QueueTask::$SETTINGS[$task_type]['tube'])
                ->put(serialize($queue_data), $priority, $delay);
            if(!$job_id){
                throw new \Exception('put data error');
            }

            return true;
        } catch (\Exception $e) {
            // 记录队列数据
            LogHelper::info('beanstalk_data', CommonHelper::jsonEncode($queue_data));

            // 记录错误信息
            $error = sprintf("Error Code: %s | %s | %s", $e->getCode(), $e->getMessage(), $e->getTraceAsString());
            LogHelper::critical('beanstalk_error', $error);

            return false;
        }
    }

}

<?php

namespace App\Tasks\Backend;

use App\Sdks\Core\System\Task\PhalBaseTask;
use App\Sdks\Library\Constants\Queue\QueueTask;
use App\Sdks\Library\Helpers\CommonHelper;
use App\Sdks\Library\Helpers\LogHelper;

/**
 * task基类
 *
 * @author dusong<1264735045@qq.com>
 */
class BaseTask extends PhalBaseTask
{
    /**
     * 执行beanstalk队列
     *
     * @param string $tube
     */
    public function runBeanstalkTask($tube)
    {
        $this->beanstalk->watch($tube);

        // 最大使用分配内存的80%
        $max_use_memory = (int)ini_get('memory_limit') * 0.8;
        while ($job = $this->beanstalk->reserve() ) {
            $body = unserialize($job->getData());

            try{
                $taskType= $body['task_type'];
                if (!isset(QueueTask::$SETTINGS[$taskType]['exec_func'])) {
                    throw new \Exception("task type: {$taskType} missing exec_func", -1);
                }

                list($class,$method) = explode("::", QueueTask::$SETTINGS[$taskType]['exec_func']);

                // 调用任务消费者
                $ret = CommonHelper::callMethod($class, $method, $body['data']);

                if (!$ret) {
                    LogHelper::error('log_queue', $body);
                }

                $this->beanstalk->delete($job);

                // 发出退出当前进程信号
                if (isset($ret['_exit_'])) {
                    LogHelper::info("log_queue", "exit_worker tube:{$tube}");
                    exit();
                }
            } catch (\Exception $e){
                $this->beanstalk->delete($job);

                $error = sprintf("Error Code: %s | %s | %s",$e->getCode(),$e->getMessage(),$e->getTraceAsString());
                LogHelper::error('log_queue', $error);
            } finally {
                // 获取当前脚本实际使用内存 退出当前脚本等待重启
                $use_memory = round(memory_get_usage(true) / 1048576, 2);
                if($use_memory >= $max_use_memory){
                    LogHelper::info("log_queue", "{$tube}_memory already use memory {$use_memory}M");
                    exit();
                }
            }
        }
    }
}

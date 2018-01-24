<?php

namespace App\Sdks\Library\Constants;

/**
 * 日志级别配置
 *
 * @author dusong<1264735045@qq.com>
 */
class LogLevel
{
    // debug信息、细粒度信息事件
    const DEBUG = 'debug';

    // 重要事件、强调应用程序的运行过程
    const INFO = 'info';

    // 一般重要性事件、执行过程中较INFO级别更为重要的信息
    //const NOTICE = 'notice';

    // 出现了非错误性的异常信息、潜在异常信息、需要关注并且需要修复
    const WARNING = 'warning';

    // 运行时出现的错误、不必要立即进行修复、不影响整个逻辑的运行、需要记录并做检测
    const ERROR = 'error';

    // 紧急情况、需要立刻进行修复、程序组件不可用
    const CRITICAL = 'critical';

    // 必级立即采取行动的紧急事件、需要立即通知相关人员紧急修复
    //const ALERT         = 'alert';

    // 系统不可用
    //const EMERGENCY     = 'emergency';
}

<?php

namespace App\Backend\Controllers;

use App\Sdks\Core\System\Controllers\PhalBaseController;

/**
 * 控制器基类
 *
 * @author dusong<1264735045@qq.com>
 */
class BaseController extends PhalBaseController
{
    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
    }

    public function initialize()
    {
        parent::initialize();
    }
}

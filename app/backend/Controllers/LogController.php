<?php

use App\Backend\Controllers\BaseController;
use App\Sdks\Library\Error\Exceptions\CustomException;
use App\Sdks\Library\Exceptions\JsonFmtException;
use App\Sdks\Library\Error\ErrorHandle;
use App\Sdks\Library\Error\handlers\Err;
use App\Sdks\Library\Error\Settings\CoreLogic;
use App\Sdks\Services\LogService;

/**
 * 日志控制器
 *
 * @author dusong<1264735045@qq.com>
 */
class LogController extends BaseController
{
    /**
     * app日志
     *
     * @throws JsonFmtException
     */
    public function appAction()
    {
        try {
            $params = [
                'log_level' => $this->request->getPost('log_level'),
                'log_type'  => $this->request->getPost('log_type'),
                'log_data'  => $this->request->getPost('log_data'),
            ];
            LogService::svaeAppLog($params);

            $this->flash->successJson();
        } catch (CustomException $e) {
            throw new JsonFmtException($e->getMessage(), $e->getCode());
        }
    }


}

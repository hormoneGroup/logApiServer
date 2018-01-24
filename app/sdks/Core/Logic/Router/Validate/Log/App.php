<?php

namespace App\Sdks\Core\Logic\Router\Validate\Log;

use App\Sdks\Core\Logic\Router\Validate\BsseValidate;
use App\Sdks\Services\LogService;

/**
 * 应用日志验证器
 *
 * @author dusong<1264735045@qq.com>
 */
class App extends BsseValidate
{
    public function rules()
    {
        return [
            ['log_level', 'required'],
            ['log_level', 'string'],
            ['log_level', 'in', LogService::getLogLevel()],
            ['log_type', 'required'],
            ['log_type', 'string'],
            ['log_data', 'required'],
            ['log_data', 'string'],
            ['log_data', 'json'],
        ];
    }

    public function messages()
    {
        return [
            'log_level.required' => '日志级别必须',
            'log_level.string'   => '日志级别数据类型不合法',
            'log_level.in'       => '日志级别非法',
            'log_type.required'  => '日志类型必须',
            'log_type.string'    => '日志类型数据类型不合法',
            'log_data.required'  => '日志数据必须',
            'log_data.string'    => '日志数据数据类型不合法',
            'log_data.json'      => '日志数据非法',
        ];
    }
}

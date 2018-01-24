<?php

namespace App\Sdks\Core\Logic\Router\Filter\Log;

use App\Sdks\Core\Logic\Router\Filter\BaseFilter;

/**
 * 应用日志过滤器
 *
 * @author dusong<1264735045@qq.com>
 */
class App extends BaseFilter
{

    public function rules()
    {
        return [
            ['log_level', 'string|trim'],
            ['log_type', 'string|trim'],
            ['log_data', 'trim'],
        ];
    }
}
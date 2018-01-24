<?php

namespace App\Sdks\Core\System\Router;

use App\Sdks\Library\Helpers\DiHelper;
use Phalcon\Mvc\Router;

/**
 * 路由类库
 *
 * @author dusong<1264735045@qq.com>
 */
class CustomRouter extends Router
{
    /**
     * 执行路由
     *
     * @return CustomRouter
     */
    public function runRouter()
    {
        $router = new self(false);

        $router->removeExtraSlashes(true);

        $router_rules = DiHelper::getSharedConfig()->routers->toArray();
        foreach ($router_rules as $pattern => $paths){
            $router->add($pattern, $paths);
        }

        return $router;
    }
}
<?php

return [
    // 应用配置
    'application'   => require __DIR__. "/extensions/application.php",

    // mysql配置
    'mysql'         => require __DIR__. "/extensions/mysql.php",

    // mongodb配置
    'mongodb'       => require __DIR__. "/extensions/mongodb.php",

    // redis配置
    'redis'        => require __DIR__. "/extensions/redis.php",

    // elastic配置
    'elastic'      => require __DIR__. "/extensions/elastic.php",

    // session配置
    'session'      => require __DIR__. "/extensions/session.php",

    // queue配置
    'queue'        => require __DIR__. "/extensions/queue.php",

    // routers配置
    'routers'      => require __DIR__. "/extensions/routers.php",

    // system配置
    'system'       => require __DIR__. "/extensions/system.php",
];

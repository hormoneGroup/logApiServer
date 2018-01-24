<?php

/**
 * FPM以及命令行公用的di服务
 */

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Session\Adapter\Libmemcached as McSessionAdapter;
use Phalcon\Session\Adapter\Redis as RedisSessionAdapter;
use Phalcon\Queue\Beanstalk;
use App\Sdks\Library\ZlFlash;
use App\Sdks\Library\ZlDbListener;
use App\Sdks\Library\ZlBackendMemcached;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Collection\Manager as collectionManager;
use App\Sdks\Library\Helpers\CommonHelper;
use Phalcon\Db\Profiler;
use Phalcon\Session\Adapter\Redis as SessionAdapter;
use App\Sdks\Core\System\Flash\CustomFlash;
use App\Sdks\Core\System\Db\CustomMysql;
use App\Sdks\Core\System\Plugins\CustomSecurity;
use Pheanstalk\Pheanstalk;

// DI shared
$is_shared = true;
if (defined('SHARED'))  {
    $is_shared = false;
}

/**
 * 注册全局配置
 */
$di->set('config', $config, $is_shared);

/**
 * 注册分析器
 */
$di->set('profiler', function() {
    return new Profiler();
}, $is_shared);


/**
 * 注册数据库句柄
 */
$di->set('db', function () use ($config) {
    // 创建事件管理器
    $em = new EventsManager();
    $em->attach('db', new CustomMysql());

    // 数据库连接
    $conf = $config->mysql->main->toArray();
    if (!isset($conf['options'][PDO::ATTR_TIMEOUT])) {
        $conf['options'][PDO::ATTR_TIMEOUT] = $config->application->mysql_connect_timeout;
    }
    $conn = new DbAdapter($conf);
    $conn->setEventsManager($em);

    return $conn;
}, $is_shared);


/**
 * 注册session句柄
 */
$di->set('session', function () use ($config) {
    $session_config = $config->session->redis->toArray();
    $session = new SessionAdapter($session_config);
    if (!$session->isStarted()) {
        $session->start();
    }

    return $session;
}, $is_shared);


/**
 * 注册collectionManager
 */
$di->set('collectionManager', function() {
    return new collectionManager();
}, $is_shared);


/**
 * 注册modelsManager
 */
$di->set('modelsManager', function() {
    return new ModelsManager();
}, $is_shared);


/**
 * 注册flash服务
 */
$di->set('flash', function() {
    $flash = new CustomFlash();

    return $flash;
}, $is_shared);


/**
 * 注册安全服务
 */
$di->set('security', function () {
        $security = new CustomSecurity();

        return $security;
}, $is_shared);


/**
 * 注册主redis服务
 */
$di->set('redis', function () use ($config) {
    $redis_config = $config->redis_server->main;
    $redis = new \Redis();
    $redis->connect($redis_config->host, $redis_config->port);
    if (isset($redis_config->auth)) {
        $redis->auth($redis_config->auth);
    }

    return $redis;
}, $is_shared);


/**
 * 注册mongodb
 */
$di->set('mongodb', function() use ($config) {
    $url = "mongodb://";
    $mongo_conn = [];
    foreach ($config->mongodb as $index => $conf) {
        if(is_numeric($index) && is_object($conf)){
            $mongo_conn[] = $conf->host . ':' . $conf->port;
        }
    }
    $url    .= implode(',', $mongo_conn);
    if (isset($config->mongodb->options)) {
        $options = (array)$config->mongodb->options;
    } else {
        $options = [];
    }

    // 正式环境加入集群复制名字
    if(CommonHelper::isPro()){
        $options['replicaSet'] = $config->mongodb->replica_set_name;
    }
    $conn = new \MongoDB\Client($url, $options);

    return $conn->selectDatabase($config->mongodb->database);
}, $is_shared);


/**
 * 注册beanstalk队列
 */
$di->set('beanstalk', function () use ($config) {
    $queue = new Pheanstalk(
        $config->queue->beanstalk->main->host, $config->queue->beanstalk->main->port,
        $config->queue->beanstalk->main->timeout
    );
    return $queue;
}, $is_shared);

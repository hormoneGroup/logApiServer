# logApiServer Framework For PHP

-------------------------------------

```
phalcon框架封装日志服务接口，上报日志数据，
记录日志数据，后期可以介入ELK服务。
```

## 环境要求
- Linux，FreeBSD，MacOS
- PHP-7.0及以上版本
- beanstalkd(队列)
- phalcon(PHP框架)
- SeasLog(日志扩展)


## 代码下载
```
git clone https://github.com/hormoneGroup/logApiServer
```

## 虚拟域名配置
```
server {
    listen      80;
    server_name dev.logApiServer.com;
    set         $root_path '/data/web/logApiServer';
    root        $root_path;

    index index.php index.html index.htm;

    try_files $uri $uri/ @rewrite;

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }

    location ~ \.php {
        # try_files    $uri =404;

        fastcgi_index  /index.php;
        fastcgi_pass   127.0.0.1:9000;

        include fastcgi_params;
        fastcgi_split_path_info       ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO       $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~* ^/(css|img|js|flv|swf|download)/(.+)$ {
        root $root_path;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 日志级别说明
```
debug (信息、细粒度信息事件)

info ( 重要事件、强调应用程序的运行过程)

warning (出现了非错误性的异常信息、潜在异常信息、需要关注并且需要修复)

error (运行时出现的错误、不必要立即进行修复、不影响整个逻辑的运行、需要记录并做检测)

critical (紧急情况、需要立刻进行修复、程序组件不可用)
```

## 日志上报
```
上报接口地址:http://dev.logApiServer.com/log/save
```

#### 1. 接口描述

>日志上报

#### 1.1.1. 请求说明

URL  |请求方式
:---:|:---:
http://dev.logApiServer.com/log/save
| POST

#### 1.1.2. 参数说明

参数名称 | 是否必填 | 描述
:---: | --- | ---
 log_level | <mark>是</mark> | 日志级别(参考日志级别说明)
  log_type | <mark>是</mark> | 日志数据类型
  log_data | <mark>是</mark> | 日志数据JSON
  
#### 1.1.3. 请求示例

```
POST /log/save HTTP/1.1
Host: dev.logApiServer.com
...
Content-Length: 83

log_level=debug&log_type=app_dubug&log_data={"name":"xxx"}
```

#### 1.1.5. 返回参数说明

>正确的json返回结果示例：

```
{
    "code": 0,
    "msg": "ok",
    "body": {
    }
}

```

>错误的json返回结果示例：

```
{
    "code": "1000",
    "msg": "error",
    "body": {
    }
}
```

>字段说明：

```
{
  "code": 返回码，取值0成功，其它表示失败,
  "msg": 描述信息,
  "body": {
  }
}
```


## 启动命令行日志队列
```
/usr/local/php/bin/php /data/web/logApiServer/app/tasks/cli.php backend.log asyncAppLog
``` 

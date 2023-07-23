### 基于Swoole 封装的`连接池`以及适配了`illuminate/database`
###### 目前基于PDO规范 支持MySQL，SQL Server，Postgres和SQLite。

#### 1. 安装
```
composer require guanhui07/database
```
#### 2. 创建`配置`实例
```php
/**
 * 创建配置
 */
(new \Guanhui07\SwooleDatabase\PDOConfig())->
withDriver('mysql')-> // 驱动类型
withHost('127.0.0.1')-> // 主机地址
withDbname('test')-> // 数据库名
withUsername('root')-> // 用户名
withPassword('123456')-> // 密码
withCharset('utf8mb4')-> // 字符集编码
setConfig('default'); // 设置全局访问(默认为default)
```
#### 3. 设置
```php
\Guanhui07\SwooleDatabase\PoolManager::addPool(64,'default'); // 设置指定连接池尺寸(连接名称默认为 default)
```

#### 4. 使用协程环境模拟Swoole 的任务执行
```php
/**
 * 开启协程(如果框架内已经开启可忽略)
 */
\Swoole\Runtime::enableCoroutine();
/**
 * 协程化IO钩子
 */
\Swoole\Coroutine::set(['hook_flags' => SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL]);
/**
* 记录开始时间
 */
$s = microtime(true);
\Swoole\Coroutine\run(function () {
    /**
     * 循环创建协程(模拟HTTP 请求 执行任务)
     */
    for ($i = 0; $i < 20; $i++) {
        \Swoole\Coroutine::create(function () {
            /**
             * 创建表
             */
//            \Guanhui07\SwooleDatabase\Manager::schema()->create('test',function(\Illuminate\Database\Schema\Blueprint $table){
//                $table->increments('id');
//                $table->string('name')->nullable()->default(1);
//                $table->timestamps();
//            });
            /**
             * 模型查询
             */
//            $lists = \Guanhui07\SwooleDatabase\Model::query()->first();
            /**
             * Connection 直接查询
             */
            $lists = \Guanhui07\SwooleDatabase\Adapter\Manager::table('bd_live_plan')->first();
            var_dump(boolval($lists));
            /**
             * 协程销毁会自动归还连接(不需要手动处理)
             */
        });
    }
});
echo '所有任务用了:' . (microtime(true) - $s) . '秒';
```

### model  用法和laravel orm 一致

```php
class UserModel extends \Guanhui07\SwooleDatabase\Adapter\Model
{
    protected $table = 'user';

}
```

## 类laravel orm DB
```php
use Guanhui07\SwooleDatabase\Adapter\Manager as DB ;

$test = DB::table('user')->where('id', '>', 1)
    ->orderBy('id', 'desc')->limit(2)->get(['id']);
print_r($test->toArray());
$test = DB::select('select 1');
var_dump($test);


```



## 我的其他包：
https://github.com/guanhui07/dcr  借鉴Laravel实现的 PHP Framework ，FPM模式、websocket使用的workerman、支持容器、PHP8特性attributes实现了路由注解、中间件注解、Laravel Orm等特性

https://github.com/guanhui07/redis Swoole模式下 Redis连接池

https://github.com/guanhui07/facade  facade、门面 fpm模式下可使用

https://github.com/guanhui07/dcr-swoole-crontab 基于swoole实现的crontab秒级定时任务

https://github.com/guanhui07/timer php定时器，参考了workerman源码 实现一个单进程(守护进程)的定时器。


https://github.com/guanhui07/database  基于 illuminate/database 做的连接池用于适配Swoole的协程环境

https://github.com/guanhui07/dcr-swoole  高性能PHP Framework ，Cli模式，基于Swoole实现，常驻内存，协程框架，支持容器、切面、PHP8特性attributes实现了路由注解、中间件注解、支持Laravel Orm等特性

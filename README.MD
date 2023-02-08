### 基于Swoole 封装的`连接池`以及适配了`illuminate/database`
###### 目前基于PDO规范 支持MySQL，SQL Server，Postgres和SQLite。

#### 1. 安装
```
composer require guanhui07/database:dev-master
```
#### 2. 创建`配置`实例
```php
/**
 * 创建配置
 */
(new \guanhui07\SwooleDatabase\PDOConfig())->
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
\guanhui07\SwooleDatabase\PoolManager::addPool(64,'default'); // 设置指定连接池尺寸(连接名称默认为 default)
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
//            \guanhui07\SwooleDatabase\Manager::schema()->create('test',function(\Illuminate\Database\Schema\Blueprint $table){
//                $table->increments('id');
//                $table->string('name')->nullable()->default(1);
//                $table->timestamps();
//            });
            /**
             * 模型查询
             */
//            $lists = \guanhui07\SwooleDatabase\Model::query()->first();
            /**
             * Connection 直接查询
             */
            $lists = \guanhui07\SwooleDatabase\Adapter\Manager::table('bd_live_plan')->first();
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
class UserModel extends \guanhui07\SwooleDatabase\Adapter\Model
{
    protected $table = 'user';

}
```

## 类laravel orm DB
```php
use guanhui07\SwooleDatabase\Adapter\Manager as DB ;

$test = DB::table('user')->where('id', '>', 1)
    ->orderBy('id', 'desc')->limit(2)->get(['id']);
print_r($test->toArray());
$test = DB::select('select 1');
var_dump($test);


```




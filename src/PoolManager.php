<?php


namespace Guanhui07\SwooleDatabase;


use Exception;
use Guanhui07\SwooleDatabase\Utils\Context;
use RuntimeException;

/**
 * 连接池管理(全局)
 * Class PoolManager
 * @package Guanhui07\SwooleDatabase
 */
class PoolManager
{
    protected static $pool = [];

    /**
     * 新增连接池
     * @param int $size
     * @param string $name
     * @throws Exception
     */
    public static function addPool($size = 64, $name = 'default'): void
    {
        if (isset(self::$pool[$name]) && self::$pool[$name] instanceof PDOPool) {
            throw new RuntimeException('Pool Exist');
        }
        $config = PDOConfig::getConfig($name);
        self::$pool[$name] = $pool = new PDOPool($config, $size);
    }

    /**
     * 获取连接池
     * @param string $name
     * @return PDOPool
     * @throws Exception
     */
    public static function getPool($name = 'default')
    {
        if (!(isset(self::$pool[$name]) && self::$pool[$name] instanceof PDOPool)) {
            self::addPool();
        }
        return self::$pool[$name];
    }

    /**
     * 归还当前协程内的所有连接
     * @throws Exception
     */
    public static function recoveryConnection()
    {
        $lists = Context::get(__CLASS__ . '_connection') === null ? [] : Context::get(PoolManager::class . '_connection');
        foreach ($lists as $item) {
            self::getPool($item['name'])->put($item['pdo']);
        }
    }
}
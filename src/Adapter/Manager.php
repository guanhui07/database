<?php


namespace guanhui07\SwooleDatabase\Adapter;

use Illuminate\Database\Connection;
use guanhui07\SwooleDatabase\PDOConfig;
use guanhui07\SwooleDatabase\Utils\Context;

/**
 * Class Manager
 * @package guanhui07\SwooleDatabase\Adapter
  * @method static Expression raw($value)
 * @method static selectOne(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static array select(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static Generator cursor(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static bool insert(string $query, array $bindings = [])
 * @method static int update(string $query, array $bindings = [])
 * @method static int delete(string $query, array $bindings = [])
 * @method static bool statement(string $query, array $bindings = [])
 * @method static int affectingStatement(string $query, array $bindings = [])
 * @method static bool unprepared(string $query)
 * @method static array prepareBindings(array $bindings)
 * @method static mixed transaction(\Closure $callback, int $attempts = 1)
 * @method static void beginTransaction()
 * @method static void rollBack()
 * @method static void commit()
 * @method static int transactionLevel()
 * @method static array pretend(\Closure $callback)
 */
class Manager extends \Illuminate\Database\Capsule\Manager
{
    /**
     * Get a fluent query builder instance.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string  $table
     * @param  string|null  $as
     * @param  string|null  $connection
     * @return QueryBuilder
     */
    public static function table($table, $as = null, $connection = null)
    {
        return static::connection($connection)->table($table, $as);
    }
    /**
     * Get a schema builder instance.
     *
     * @param  string|null  $connection
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function schema($connection = null)
    {
        return static::connection($connection)->getSchemaBuilder();
    }
    /**
     * Get a connection instance from the global manager.
     *
     * @param  string|null  $connection
     * @return \Illuminate\Database\Connection
     */
    public static function connection($name = null)
    {
        if($name === null){
            $name = 'default';
        }
        return ConnectionFactory::getConnection($name);
    }
    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return static::connection()->$method(...$parameters);
    }
}

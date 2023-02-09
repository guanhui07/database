<?php

namespace Guanhui07\SwooleDatabase\Utils;

use Swoole\Coroutine;

/**
 * Class Context
 * @package Guanhui07\SwooleDatabase\Utils
 */
class Context
{
    protected static $pool = [];

    static function get($key)
    {
        $cid = Coroutine::getuid();
        if ($cid < 0) {
            return null;
        }
        if (isset(self::$pool[$cid][$key])) {
            return self::$pool[$cid][$key];
        }
        return null;
    }

    static function put($key, $item)
    {
        $cid = Coroutine::getuid();
        if ($cid > 0) {
            self::$pool[$cid][$key] = $item;
        }
    }

    /**
     * @return bool
     */
    static function inCoroutine(){
        return Coroutine::getuid()>0;
    }

    static function delete($key = null)
    {
        $cid = Coroutine::getuid();
        if ($cid > 0) {
            if ($key) {
                unset(self::$pool[$cid][$key]);
            } else {
                unset(self::$pool[$cid]);
            }
        }
    }
}
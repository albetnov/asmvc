<?php

namespace App\Asmvc\Core;

use Phpfastcache\Helper\Psr16Adapter;

class Cache
{
    private static Psr16Adapter $cacheDriver;

    public static function boot(string $adapter = 'Files'): void
    {
        self::$cacheDriver = new Psr16Adapter($adapter);
    }

    public function exist(string $key): bool
    {
        return self::$cacheDriver->has($key);
    }

    public function cache(string $key, ?string $value = null)
    {
        if ($value) {
            return self::$cacheDriver->set($key, $value);
        }

        return self::$cacheDriver->get($key);
    }

    public function getCacheInstance()
    {
        return self::$cacheDriver;
    }
}

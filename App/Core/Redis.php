<?php

namespace App\Asmvc\Core;

use Predis\Client;

class Redis
{
    /**
     * A function to connect to the machine redis.
     */
    public function connect(): Client
    {
        $redisCfg = config('redis');
        $redis = new Client([
            'scheme' => 'tcp',
            'host' => $redisCfg['REDIS_HOST'],
            'port' => $redisCfg['REDIS_PORT']
        ]);
        $redis->connect();
        $redisPass = $redisCfg['REDIS_AUTH_PASS'];
        if (!empty($redisPass)) {
            $redis->auth($redisPass);
        }
        $redis->select($redisCfg['REDIS_DATABASE']);
        return $redis;
    }

    /**
     * Function to check whenever key is exist or not.
     * @return mixed
     */
    public function checkKey(string $key, bool $get = false): string | bool
    {
        $redis = $this->connect();
        if ($redis->exists($key) !== 0) {
            if ($get) {
                return $redis->get($key);
            }
            return true;
        }

        return false;
    }

    /**
     * Function to set and get redis key
     * @return mixed
     */
    public function redisKey(string $key, mixed $value = null, bool $list = false, int $expire = null): bool
    {
        $redis = $this->connect();
        if (is_null($value)) {
            if (str_contains($key, '*')) {
                return $redis->keys($key);
            }
            return $this->checkKey($key, true);
        }
        $attempt = $list ? $redis->lpush($key, $value) : $redis->set($key, $value);
        if (!is_null($expire)) {
            $redis->expire($key, $expire);
        }
        return (bool) $attempt;
    }

    /**
     * Get key expires
     */
    public function getExpires(string $key): int
    {
        $redis = $this->connect();
        return $redis->ttl($key);
    }

    /**
     * Delete a key
     * @param mixed $key
     */
    public function flush(?string $key = null): bool
    {
        if (is_null($key)) {
            $redis = $this->connect()->flushDb();
            if ($redis) {
                return true;
            }
        } elseif (!is_null($key)) {
            $redis = $this->connect()->del($key);
            if ($redis !== 0) {
                return true;
            }
        }

        return false;
    }
}

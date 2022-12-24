<?php

namespace Albet\Asmvc\Core;

class Redis
{
    /**
     * A function to connect to the machine redis.
     * @return \Redis
     */
    public function connect(): \Redis
    {
        $redis = new \Redis();
        $redis->connect(env('REDIS_SERVER', '127.0.0.1'), env('REDIS_PORT', '6379'), 2.5, NULL, 100);
        $redisUser = env('REDIS_AUTH_USRE');
        $redisPass = env('REDIS_AUTH_PASS');
        if (!empty($redisUser) && !empty($redisPass)) {
            $redis->auth(['user' => $redisUser, 'pass' => $redisPass]);
        } else if (empty($redisUser) && !empty($redisPass)) {
            $redis->auth($redisPass);
        }
        $redis->select(env('REDIS_DB', 0));
        return $redis;
    }

    /**
     * Function to check whenever key is exist or not.
     * @param string $key
     * @param bool $get
     * @return mixed
     */
    public function checkKey(string $key, bool $get = false): string | bool
    {
        $redis = $this->connect();
        if ($redis->exists($key)) {
            if ($get) {
                return $redis->get($key);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to set and get redis key
     * @param string $key
     * @param mixed $value
     * @param boolean $list
     * @param int $expire
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
        if ($list) {
            $attempt = $redis->lpush($key, $value);
        } else {
            $attempt = $redis->set($key, $value);
        }
        if (!is_null($expire)) {
            $redis->expire($key, $expire);
        }
        if ($attempt) {
            return true;
        }
    }

    /**
     * Get key expires
     * @param string $key
     * @return int
     */
    public function getExpires(string $key): int
    {
        $redis = $this->connect();
        return $redis->ttl($key);
    }

    /**
     * Delete a key
     * @param mixed $key
     * @return bool
     */
    public function flush(?string $key = null): bool
    {
        if (is_null($key)) {
            $redis = $this->connect()->flushDb();
            if ($redis) {
                return true;
            }
        } else if (!is_null($key)) {
            $redis = $this->connect()->del($key);
            if ($redis) {
                return true;
            }
        }
    }
}

<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\Redis;
use PHPUnit\Framework\TestCase;

class connectRedisTest extends TestCase
{
    private $redis;

    protected function setUp(): void
    {
        $redis = $this->redis = new Redis;
    }

    public function testattemptRedisConnect()
    {
        $redis = $this->redis->connect();
        $this->assertSame(true, $redis->ping(), 'Connection to redis failed.');
    }

    public function testInsertDataToRedis()
    {
        $redis = $this->redis->connect();
        $this->assertTrue($redis->set('TRYING', 'nyooba'), 'Inserting data to redis failed');
    }

    public function testgetDataFromRedis()
    {
        $redis = $this->redis->connect();
        // print_r($redis->keys('*'));
        $this->assertIsArray($redis->keys('*'), 'Failed fetching data to redis.');
    }

    public function testRedisExpired()
    {
        $redis = $this->redis->connect();
        $redis->set("TES-EXPIRED", 'nyoba ekspiredkan');
        $redis->expire('TES-EXPIRED', 300);
    }

    public function testGetExpiresNative()
    {
        $redis = $this->redis->connect();
        echo "Expired: " . $redis->ttl('TES-EXPIRED');
    }

    public function testSetRedis()
    {
        $redis = $this->redis->redisKey('TES-DARI-redis', 'coba aja ya', false, 500);
        $this->assertTrue($redis, 'Failed inserting redis');
    }

    public function testPushKey()
    {
        $redis = new Redis;
        $redis->redisKey('TES-LIST', 'OKE COBA LAGI', true, 500);
        $redis->redisKey('TES-LIST', 'testsyd', true);
        $this->assertIsObject($redis, 'Failed pushing redis');
    }

    public function testGetredis()
    {
        $redis = $this->redis->redisKey('TES-DARI-redis');
        $this->assertIsString($redis, 'Failed fetching redis');
    }

    public function testGetExpiredredis()
    {
        $redis = $this->redis->getExpires('TES-DARI-redis');
        $this->assertIsInt($redis, 'Failed fetching expired redis data');
    }

    public function testFlushRedis()
    {
        $redis = $this->redis->flush();
        $this->assertTrue($redis, 'Failed to erase redis data');
    }
}

<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\Redis;
use Albet\Asmvc\Core\SessionManager;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    protected function setUp(): void
    {
        $_ENV['SESSION_TYPE'] = 'redis';
        try {
            (new Redis)->connect();
        } catch (\RedisException $e) {
            $this->markTestSkipped('Redis connection cannot be made.');
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testIniSaveHandler()
    {
        SessionManager::runSession();
        $ini = ini_get('session.save_handler');
        $this->assertSame('redis', $ini, 'Session Save Handler is not set to redis');
    }

    /**
     * @runInSeparateProcess
     */
    public function testIniSavePath()
    {
        SessionManager::runSession();
        $ini = ini_get('session.save_path');
        $this->assertStringStartsWith('tcp://', $ini, 'Session save path is not set to redis');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunSessionFromRedis()
    {
        SessionManager::runSession();
        session_start();
        $_SESSION['test'] = 'testSesiDenganRedis';
        $this->assertSame('testSesiDenganRedis', $_SESSION['test'], 'Fetching session from redis failed.');
    }
}

<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\SessionManager;
use PHPUnit\Framework\TestCase;

class normalSessionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGetSessName()
    {
        SessionManager::runSession();
        $ini = ini_get('session.name');
        $this->assertSame('ASMVCSESSID', $ini, 'Setting ini of session name failed');
    }

    /**
     * @runInSeparateProcess
     */
    public function testSession()
    {
        SessionManager::runSession();
        $_SESSION['test'] = 'normalPHPSession';
        $this->assertSame('normalPHPSession', $_SESSION['test'], 'Failed setting session.');
    }
}

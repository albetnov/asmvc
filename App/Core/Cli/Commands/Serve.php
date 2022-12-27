<?php

namespace App\Asmvc\Core\Cli\Commands;

use App\Asmvc\Core\Cli\Cli;
use App\Asmvc\Core\Cli\ExecDisabledException;

class Serve extends Cli
{
    /**
     * @var string $command
     * @var string $desc
     */
    protected $command = 'serve';
    protected $desc = 'Start a ASMVC Development Server';

    /**
     * Register the command
     */
    public function register()
    {
        if (!function_exists('exec')) {
            throw new ExecDisabledException();
        }
        $port = 9090;
        $default = @fsockopen('localhost', $port);
        while (is_resource($default)) {
            echo "Port in use. (:{$port})\n";
            $port++;
            echo "Forwanding to (:{$port})\n";
            fclose($default);
        }
        echo "ASMVC Development Server Start... (http://localhost:{$port})\n";
        exec('php asmvc run:server && php -S localhost:' . $port . ' public/index.php');
    }
}

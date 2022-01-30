<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class Serve extends BaseCli
{
    protected $command = 'serve';
    protected $desc = 'Start a ASMVC Development Server';

    public function register()
    {
        if (!function_exists('exec')) {
            throw new \Exception("Exec() is not detected. Please activate it in php.ini");
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
        exec('php -S localhost:' . $port . ' public/index.php');
    }
}

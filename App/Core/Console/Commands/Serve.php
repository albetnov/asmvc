<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\ExecDisabledException;
use App\Asmvc\Core\Console\FluentCommandBuilder;

class Serve extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder
            ->setName('serve')
            ->setDesc('Serve the web application');
    }

    public function handler($input, $output): int
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
        $this->info("Serving your application at (http://localhost:{$port})</div>");
        exec('php -S localhost:' . $port . ' public/index.php');
        return Command::SUCCESS;
    }
}

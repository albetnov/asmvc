<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\ExecDisabledException;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentOptionalParamBuilder;

class Serve extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder
            ->setName('serve')
            ->setDesc('Serve the web application')
            ->addOptionalParam(fn (FluentOptionalParamBuilder $opb) => $opb->setName('port')
                ->setDesc("Customise the Port where server going to served.")
                ->setInputTypeRequired());
    }

    public function handler($input, $output): int
    {
        if (!function_exists('exec')) {
            throw new ExecDisabledException();
        }

        $port = $input->getOption('port') ? $input->getOption('port') : 9090;
        $default = @fsockopen('localhost', $port);
        while (is_resource($default)) {
            $this->badgeWarn("Port in use. (:{$port})\n");
            $port++;
            $this->badgeInfo("Forwanding to (:{$port})\n");
            fclose($default);
        }
        $this->info("Serving your application at (http://localhost:{$port})</div>");
        exec('php -S localhost:' . $port . ' public/index.php');
        return Command::SUCCESS;
    }
}

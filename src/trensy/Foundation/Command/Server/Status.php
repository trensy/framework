<?php
/**
 * Created by PhpStorm.
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         1.0.7
 */

namespace Trensy\Foundation\Command\Server;

use Trensy\Console\Input\InputInterface;
use Trensy\Console\Output\OutputInterface;
use Trensy\Foundation\Command\Base;
use Trensy\Console\Input\InputOption;

class Status extends Base
{
    protected function configure()
    {
        $this
            ->setName('server:status')
            ->setDescription('show all server status');
            $this->addOption('--option', '-o', InputOption::VALUE_OPTIONAL, 'diy server option ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ServerBase::operate("status", $this, $input);
    }
}
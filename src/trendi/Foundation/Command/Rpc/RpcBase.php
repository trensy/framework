<?php
/**
 * User: Peter Wang
 * Date: 16/9/15
 * Time: 下午10:19
 */

namespace Trendi\Foundation\Command\Rpc;

use Trendi\Config\Config;
use Trendi\Rpc\RpcSerialization;
use Trendi\Rpc\RpcServer;
use Trendi\Support\Arr;
use Trendi\Support\Dir;
use Trendi\Support\ElapsedTime;

class RpcBase
{

    public static function operate($cmd, $output, $input)
    {
        ElapsedTime::setStartTime(ElapsedTime::SYS_START);
        $root = Dir::formatPath(ROOT_PATH);
        Config::setConfigPath($root . "config");
        $config = Config::get("server.rpc");
        $appName = Config::get("server.name");

        if (!$appName) {
            $output->writeln("<info>server.name not config</info>");
            exit(0);
        }

        if (!$config) {
            $output->writeln("<info>rpc config not config</info>");
            exit(0);
        }

        if (!isset($config['server'])) {
            $output->writeln("<info>rpc.server config not config</info>");
            exit(0);
        }

        if ($input->hasOption("daemonize")) {
            $daemonize = $input->getOption('daemonize');
            $config['server']['daemonize'] = $daemonize == 0 ? 0 : 1;
        }

        if (!isset($config['server']['host'])) {
            $output->writeln("<info>rpc.server.host config not config</info>");
            exit(0);
        }

        if (!isset($config['server']['port'])) {
            $output->writeln("<info>rpc.server.port config not config</info>");
            exit(0);
        }

        self::doOperate($cmd, $config, $root, $appName, $output);
    }


    public static function doOperate($command, array $config, $root, $appName, $output)
    {
        $defaultConfig = [
            'daemonize' => 0,
            //worker数量，推荐设置和cpu核数相等
            'worker_num' => 2,
            //reactor数量，推荐2
            'reactor_num' => 2,
            "dispatch_mode" => 2,
            'static_path' => $root . '/public',
            "gzip" => 4,
            "static_expire_time" => 86400,
            "task_worker_num" => 5,
            "task_fail_log" => "/tmp/task_fail_log",
            "task_retry_count" => 2,
            "serialization" => 1,
            "mem_reboot_rate" => 0.8,
            //以下配置直接复制，无需改动
            'open_length_check' => 1,
            'package_length_type' => 'N',
            'package_length_offset' => 0,
            'package_body_offset' => 4,
            'package_max_length' => 2000000,
        ];

        $config['server'] = Arr::merge($defaultConfig, $config['server']);
//        $config['server']["open_length_check"] = 0;
        $serverName = $appName . "-rpc-master";
        exec("ps axu|grep " . $serverName . "$|awk '{print $2}'", $masterPidArr);
        $masterPid = $masterPidArr ? current($masterPidArr) : null;

        if ($command === 'start' && $masterPid) {
            $output->writeln("<info>[$serverName] already running</info>");
            return;
        }

        if ($command !== 'start' && $command !== 'restart' && !$masterPid) {
            $output->writeln("<info>[$serverName] not run</info>");
            return;
        }
        // execute command.
        switch ($command) {
            case 'status':
                if ($masterPid) {
                    $output->writeln("<info>[$serverName] already running</info>");
                } else {
                    $output->writeln("<info>[$serverName] run</info>");
                }
                break;
            case 'start':
                self::start($config, $root, $appName);
                break;
            case 'stop':
                self::stop($appName);
                $output->writeln("<info>[$serverName] stop success </info>");
                break;
            case 'restart':
                self::stop($appName);
                self::start($config, $root, $appName);
                break;
            default :
                return "";
        }
    }


    protected static function stop($appName)
    {
        $killStr = $appName . "-rpc";
        exec("ps axu|grep " . $killStr . "|awk '{print $2}'|xargs kill -9", $masterPidArr);
    }

    protected static function start($config, $root, $appName)
    {
        $swooleServer = new \swoole_server($config['server']['host'], $config['server']['port']);
        $route = new RpcSerialization($config['server']['serialization'], $config['server']['package_body_offset']);
        $obj = new RpcServer($swooleServer, $route, $config, $root, $appName);
        $obj->start();
    }
}
<?php
/**
 * 普通job服务
 *
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         3.0.0
 */

/**
 *   crontab增加 * * * * * path/to/phpbin path/to/trensy jobd:run >> /dev/null 2>&1
 */
namespace Trensy\Foundation\Command\Artisan;

use Trensy\Config;
use Trensy\Di;
use Trensy\Foundation\Command\Base;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trensy\Log;
use Trensy\Support\AliasLoader;
use Trensy\Support\Tool;

class Jobd extends Base
{

    protected function configure()
    {
        $this->setName('jobd:run')
            ->setDescription('start the jobd server ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Di::set("task", ['class'=>\Trensy\Server\Task::class]);
        AliasLoader::getInstance(['Task'=>\Trensy\Server\TaskFacade::class])->register();

        $jobList = Config::get("app.jobd");
        $realip = Tool::getCLientIp();
        $realip = current($realip);
        Log::sysinfo("local ip :". $realip);
        if($jobList){
            foreach ($jobList as &$v){
                $ips = isset($v['ip'])?$v['ip']:null;
                if($ips){
                    if(!in_array($realip, $ips)){
                        unset($v);
                        Log::sysinfo("local ip not allow run job");
                        continue;
                    }
                }
            }
        }

        if(!$jobList){
            Log::show(" no jobs");
            exit;
        }

        foreach ($jobList as $pv){
            $rule = isset($pv['rule']) ? $pv['rule'] : null;
            $start = isset($pv['start']) ? $pv['start'] : null;
            $end = isset($pv['end']) ? $pv['end'] : null;

            if(!$rule) return $rule;

            if($start && (time() < strtotime($start))) continue ;
            if($end && (time() > strtotime($end))) continue ;

            if(date('Y-m-d H:i:s') != date($rule)){
                continue ;
            }

            $class = isset($pv['class']) && $pv['class']?$pv['class']:null;
            if(!$class) continue ;

            $taskObj = Di::get($class);

            if (!is_object($taskObj)) {
                Log::error("jobObj unvalidate :" . $class);
                continue ;
            }
            $taskObj->perform();
        }
    }

}
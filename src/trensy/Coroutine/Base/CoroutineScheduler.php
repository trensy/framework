<?php
/**
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         1.0.7
 */

namespace Trensy\Coroutine\Base;


class CoroutineScheduler
{

    protected static $taskQueue = null;
 static $i=0;

    public function __construct()
    {
        if (!self::$taskQueue) {
            self::$taskQueue = new \SplQueue();
        }
    }

    public function newTask(\Generator $coroutine)
    {

        $task = new CoroutineTask($coroutine);
        self::$taskQueue->enqueue($task);
    }

    public function schedule(CoroutineTask $task)
    {

        self::$taskQueue->enqueue($task);
    }

    public function run()
    {
        while (!self::$taskQueue->isEmpty()) {
            $task = self::$taskQueue->dequeue();
            $task->work($task->getRoutine());
        }
    }


}
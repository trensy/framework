<?php

/**
 * User: Peter Wang
 * Date: 16/9/13
 * Time: 下午3:18
 */
namespace Trendi\Test\Controller;

use Trendi\Mvc\Controller;
use Trendi\Job\Job;

class Index extends Controller
{

    public function index($say)
    {
        
        $this->view->say = $say;
        
//        return $response->redirect("/index/test");

        return $this->render("index/index");
    }

    public function test()
    {
//        $this->test2();
//        dump(\Context::response());
//        dump(\Context::getFacadeApplication());

//        \Task::email("wangkaihui@putao.com",array('site-monitor@putao.com' => 'Site Monitor'),"test","hello world");
        
//        $str = "
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//            asdasdaasd34gerhdtfhyyukyuoyuoiyouiouiotyjhfdhdfhfdhdrhfdhdfghdfghdfghdfghdfghfgh
//
//        ";
//        $client = new \Trendi\Rpc\RpcClient("127.0.0.1", 9000,1);
//        $data = $client->get("/rpc/index/index/kaihui", ["test"=>$str]);
//        dump($data);

//        $userDao = new \Trendi\Test\Lib\Dao\UserWxDao();
//        $data = $userDao->test();
//
        $userDao = new \Trendi\Test\Lib\Dao\UserWxDao();
        $data = $userDao->test();
//        dump($data);
        $rs = cache()->set("wang", "hello world");
//        dump(posix_getpid()."-hello:".$rs);
//        $data = cache()->get("wang");
        $rs = cache()->set("wang", "wangkaihui");
//        dump(posix_getpid()."-wang:".$rs);
        $data = cache()->get("wang");
//        dump(posix_getpid()."-".$data);
//
        $_SESSION["test"] = "trendi";
        debug($_SESSION);
//        \Job::add("clearlog",new \Trendi\Test\Lib\Job\Test("job_start"), date('Y-m-d H:i:s'), "*/2 * * * * *");
        $this->view->test = "test";
        
        return $this->render("index/test");
    }

}
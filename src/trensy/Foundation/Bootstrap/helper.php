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

if (!function_exists('url')) {
    /**
     *  根据路由名称获取网址
     *
     * @param $routeName
     * @param array $params
     * @return string
     */
    function url($routeName, $params = [])
    {
        return \Trensy\Mvc\Route\RouteMatch::getInstance()->url($routeName, $params);
    }
}

if (!function_exists('redis')) {
    /**
     *  获取redis 对象
     *
     * @return \Trensy\Foundation\Storage\Redis
     */
    function redis()
    {
        return new \Trensy\Foundation\Storage\Redis();
    }
}

if (!function_exists('config')) {
    /**
     *  config 对象
     *
     * @return mixed
     */
    function config()
    {
        return new \Trensy\Config\Config();
    }
}

if (!function_exists('session')) {
    /**
     *  session 对象
     *
     * @return mixed
     */
    function session()
    {
        return \Trensy\Foundation\Bootstrap\Session::getInstance();
    }
}

if (!function_exists('cache')) {
    /**
     * 缓存对象
     * @return \Trensy\Cache\Adapter\RedisCache;
     */
    function cache()
    {
        return new \Trensy\Cache\Adapter\RedisCache();
    }
}

if (!function_exists('mcache')) {
    /**
     * 缓存对象
     * @return \Trensy\Cache\Adapter\MemCache;
     */
    function mcache()
    {
        return new \Trensy\Cache\Adapter\MemCache();
    }
}

if (!function_exists('syscache')) {
    /**
     * 缓存对象
     * @return \Trensy\Cache\Adapter\ApcCache;
     */
    function syscache()
    {
        return new \Trensy\Cache\Adapter\ApcCache();
    }
}


if (!function_exists('dump')) {
    /**
     * 输出
     * @return string;
     */
    function dump($str, $isReturn=false)
    {
        if(!$isReturn){
            return \Trensy\Support\Log::show($str);
        }
        ob_start();
        \Trensy\Support\Log::show($str);
        $msg = ob_get_clean();
        return $msg;
    }
}

if (!function_exists('debug')) {
    /**
     * 输出
     * @return string;
     */
    function debug($str, $isReturn=false)
    {
        if(!$isReturn){
            return \Trensy\Support\Log::debug($str);
        }
        ob_start();
        \Trensy\Support\Log::debug($str);
        $msg = ob_get_clean();
        return $msg;
    }
}

if (!function_exists('page404')) {
    /**
     * 404错误
     */
    function page404($str='')
    {
        throw new \Trensy\Support\Exception\Page404Exception($str);
    }
}

if (!function_exists('throwExit')) {
    /**
     * 断点
     */
    function throwExit($str=null)
    {
        $str && dump($str);
        throw new \Trensy\Support\Exception\RuntimeExitException("exit");
    }
}

if (!function_exists('l')) {
    /**
     * 多语言
     */
    function l($str, $params=[])
    {
        return \Trensy\Support\Lang::get($str, $params);
    }
}

if (!function_exists('array_isset')) {
    /**
     * isset 
     */
    function array_isset($arr, $key, $default=null)
    {
        return isset($arr[$key]) ? $arr[$key]:$default;
    }
}

if (!function_exists('trans')) {
    /**
     * isset
     */
    function trans($arr)
    {
        return  \Trensy\Support\Serialization\Serialization::get()->trans($arr);
    }
}

if (!function_exists('xtrans')) {
    /**
     * isset
     */
    function xtrans($arr)
    {
        return  \Trensy\Support\Serialization\Serialization::get()->xtrans($arr);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 16/7/22
 * Time: 下午6:27
 */

namespace Trendi\Foundation\Command\Artisan;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trendi\Support\Log;

class CreateProject extends Command
{
    protected function configure()
    {
        $this->setName('create:project')
            ->setDescription('create project');
        $this->addOption('--name', '-n', InputOption::VALUE_REQUIRED, 'project name ?');
        $this->addOption('--path', '-p', InputOption::VALUE_NONE, 'project path ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('name');
        $path = $input->getOption('path');
        $path = $path?$path:__DIR__."/".$name;

        if (!is_dir($path)) {
            mkdir($path);
        } else {
            Log::error("project existed!");
            return true;
        }

        $this->xCopy(__DIR__ . "/_dist/application", $path);
        chmod($path, 0777);
        chmod($path . "/storage", 0777);
        chmod($path . "/storage/tplcompile", 0777);

        //替换扩展名
        $this->changeExt($path);
        //名字替换
        $this->batchReplace($path, "/\$\{Name\}/", ucfirst($name));
        $this->batchReplace($path, "/\$\{name\}/", $name);
    }

    protected function xCopy($source, $destination, $child = 1)
    {
        if (!is_dir($source)) {
            return false;
        }
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $handle = dir($source);
        while ($entry = $handle->read()) {
            if (($entry != ".") && ($entry != "..")) {
                if (is_dir($source . "/" . $entry)) {
                    if ($child) {
                        $this->xCopy($source . "/" . $entry, $destination . "/" . $entry, $child);
                    }
                } else {
                    copy($source . "/" . $entry, $destination . "/" . $entry);
                }
            }
        }
        return true;
    }

    protected function changeExt($source, $ext = 'dist')
    {
        if (!is_dir($source)) {
            return false;
        }
        $handle = dir($source);
        while ($entry = $handle->read()) {
            if (($entry != ".") && ($entry != "..")) {
                if (is_dir($source . "/" . $entry)) {
                    $this->changeExt($source . "/" . $entry, $ext);
                } else {
                    $pathinfo = pathinfo($source . "/" . $entry);
                    if ($pathinfo['extension'] == $ext) {
                        rename($source . "/" . $entry, str_replace(".".$ext, "", $source . "/" . $entry));
                        Log::sysinfo(str_replace(".".$ext, "", $source . "/" . $entry) . " created");
                    }
                }
            }
        }
        return true;
    }

    protected function batchReplace($sourcePath, $reg, $replaceTo, $ext = "php,json")
    {
        if (!is_dir($sourcePath)) {
            return false;
        }

        $handle = dir($sourcePath);
        while ($entry = $handle->read()) {
            if (($entry != ".") && ($entry != "..")) {
                $tmpPath = $sourcePath . "/" . $entry;
                if (is_dir($tmpPath)) {
                    $this->batchReplace($tmpPath, $reg, $replaceTo, $ext);
                } else {
                    //开始替换
                    $pathinfo = pathinfo($tmpPath);
                    $extArr = explode(",", $ext);
                    if (isset($pathinfo['extension']) && in_array($pathinfo['extension'], $extArr)) {
                        $tmpData = file_get_contents($tmpPath);
                        $tmpData = preg_replace($reg, $replaceTo, $tmpData);
                        file_put_contents($tmpPath, $tmpData);
                    }
                }
            }
        }
        return true;
    }
}
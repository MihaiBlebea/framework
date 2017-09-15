<?php

namespace Framework\Factory;

use Framework\Interfaces\FactoryInterface;
use Framework\Injectables\Injector;

class CommandFactory implements FactoryInterface
{
    private static $namespace;

    public static function init()
    {
        $config = Injector::resolve("Config");
        $config = $config->getConfig("application");
        static::$namespace = $config["command_namespace"];
    }

    public static function build($type, $path = "")
    {
        static::init();

        if($path == "")
        {
            $className = static::$namespace . ucfirst($type) . "Command";
        } elseif($path == "framework"){
            $className = "Framework\\Commands\\" . ucfirst($type) . "Command";
        } else {
            throw new Exception("Unknown path to factory", 1);
        }

        if($type == "")
        {
            throw new \Exception('No command found');
        } else {
            if(class_exists($className))
            {
                return new $className();
            } else {
                throw new \Exception('Command class not found.');
            }
        }
    }

}

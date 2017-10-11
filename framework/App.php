<?php

namespace Framework;

use Framework\Injectables\Injector;
use Framework\Log\Logger;
use Framework\Factory\EventFactory;
use Framework\Factory\ListenerFactory;
use Framework\Configs\Config;

class App
{
    public $components = array();

    private $env = null;

    private $booted = false;

    private $routerActivated = false;

    private $errorHandlerActivated = false;

    public function __construct()
    {
        $config = new Config();

        $this->env = $config->getConfig("application")["app_environment"];
        $this->components = $config->getConfig("component")["components"];
    }

    public function boot()
    {
        foreach($this->components as $namespace)
        {
            $component = new $namespace();
            $component->boot();
        }
        $this->booted = true;
    }

    public function init()
    {
        foreach($this->components as $index => $namespace)
        {
            $instance = Injector::resolve($index);
            $component = new $namespace();

            if($index == "Whoops")
            {
                if($this->env == "development")
                {
                    $component->run($instance);
                } else {
                    error_reporting(0);
                }
            } else {
                $component->run($instance);
            }
        }
    }

    public function testApp()
    {
        return [
            "booted" => $this->booted,
            "routerActivated" => $this->routerActivated,
            "errorHandlerActivated" => $this->errorHandlerActivated
        ];
    }
}

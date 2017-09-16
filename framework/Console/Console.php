<?php

namespace Framework\Console;

use Framework\Factory\CommandFactory;

class Console
{
    private $argv;

    private $argc;

    private $commandList = [
        "list"              => "List all commands of the frame app",
        "create:controller" => "Build a Controller class",
        "create:model"      => "Build a Model class",
        "create:event"      => "Build an Event class",
        "create:listener"   => "Build a Listener class",
        "create:email"      => "Build an Email class",
        "create:manager"    => "Build a Manager class",
        "create:component"  => "Build a Component class",
        "create:rule"       => "Build a Rule class"
    ];

    public function __construct($argv, $argc)
    {
        $this->argv = $argv;
        $this->argc = $argc;

        if($argv[0] == "frame" && $argv[1] == "list" && $argc == 2)
        {
            $this->assembleCommand($argv[1], $this->commandList);
        } elseif($argv[0] == "frame" && strpos($argv[1], "create:") !== false && $argc == 3) {
            $this->assembleCommand($argv[1], $argv[2]);
        } else {
            echo "\n\n\e[0;31m This command does not exist !\n\n";
        }
    }

    public function getArgumentsValue()
    {
        return $this->argv;
    }

    public function getArgumentsCount()
    {
        return $this->argc;
    }

    private function assembleCommand($command, $name = "")
    {
        if(array_key_exists($command, $this->commandList))
        {
            if($command == "list")
            {
                $this->callCommandClass("ListMenu", $name);
                die();
            }

            $parts = explode(":", $command);
            $class = "";

            foreach($parts as $part)
            {
                $class .= ucfirst($part);
            }
            $this->callCommandClass($class, $name);
        } else {
            echo "\n\n\e[0;31mThis command does not exist ! \n" .
                 "Please type << php frame list >> to get a list of the commands \n\n";
        }
    }

    private function callCommandClass($class, $name)
    {
        $class = CommandFactory::build($class, "framework");
        call_user_func_array(array($class, "input"), ["name" => $name]);
    }
}

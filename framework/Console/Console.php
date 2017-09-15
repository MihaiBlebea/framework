<?php

namespace Framework\Console;

use Framework\Factory\CommandFactory;

class Console
{
    private $argv;

    private $argc;

    private $commandList = [
        "create:controller" => "Build a Controller class",
        "create:model"      => "Build a Model class",
        "create:event"      => "Build an Event class"
    ];

    public function __construct($argv, $argc)
    {
        $this->argv = $argv;
        $this->argc = $argc;

        if($argv[0] == "frame" && $argv[1] == "list" && $argc == 2)
        {
            $this->getListMenu();
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

    private function assembleCommand($command, $name)
    {
        if(array_key_exists($command, $this->commandList))
        {
            $parts = explode(":", $command);
            $class = "";

            foreach($parts as $part)
            {
                $class .= ucfirst($part);
            }
            $this->callCommandClass($class, $name);
        } else {
            echo "\n\n\e[0;31m This command does not exist ! \n" .
                 "Please type << php frame list >> to get a list of the commands \n\n";
        }
    }

    private function callCommandClass($class, $name)
    {
        $class = CommandFactory::build($class, "framework");
        call_user_func_array(array($class, "input"), ["name" => $name]);
    }

    private function getListMenuLenght()
    {
        return max(array_map('strlen', array_keys($this->commandList)));
    }

    public function getListMenu()
    {
        $maxLength = $this->getListMenuLenght();

        echo "Select you command for me, master:\n\n";
        foreach($this->commandList as $key => $value)
        {
            echo "\x1b[32m" . "php frame " . $key . str_repeat(" ", $maxLength - strlen($key)) . "\e[0m => " . $value . "\n\n";
        }
    }
}

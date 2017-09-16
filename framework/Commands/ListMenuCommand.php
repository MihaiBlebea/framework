<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class ListMenuCommand extends Command implements CommandInterface
{
    private $commandList = array();

    public function input($payload)
    {
        $this->commandList = $payload;
        $this->process();
    }

    public function process()
    {
        $maxLength = $this->getListMenuLenght();
        $this->getListMenu($maxLength);
    }

    private function getListMenuLenght()
    {
        return max(array_map('strlen', array_keys($this->commandList)));
    }

    public function getListMenu($maxLength)
    {
        echo "\n\nSelect you command for me, master:\n\n";
        foreach($this->commandList as $key => $value)
        {
            echo "\x1b[32m" . "php frame " . $key . str_repeat(" ", $maxLength - strlen($key)) . "\e[0m => " . $value . "\n\n";
        }
    }
}

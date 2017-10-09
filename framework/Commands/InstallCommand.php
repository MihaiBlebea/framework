<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;

class InstallCommand extends Command implements CommandInterface
{
    public $application;
    
    public function input($payload)
    {
        $this->process();
    }

    public function process()
    {
        $this->application = $this->copyApplication();

    }

    private function copyApplication()
    {
        $file = "../config/application.exemple.php";
        $newfile = "../config/application.php";

        if(!copy($file, $newfile))
        {
            return false;
        } else {
            return $newfile
        }
    }

    private function checkAllFiles()
    {
        $file = file_exists($this->completePath);
        if($file == true)
        {
            $this->output("success", "Success, " . $this->payload . " model created !");
        } elseif($file == false) {
            $this->output("error", "Error, file was not created !");
        } else {
            echo "I have nothing to say...";
        }
    }

}

<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class CreateControllerCommand extends Command implements CommandInterface
{
    private $payload;

    private $path = __APP_ROOT__ . "/../src/Controllers";

    public function input($payload)
    {
        $this->payload = (strpos($payload, "Controller") !== false) ? ucfirst($payload) : ucfirst($payload) . "Controller";
        $this->completePath = $this->path . "/" . $this->payload . ".php";
        $this->process();
    }

    public function process()
    {
        if(file_exists($this->completePath) == true)
        {
            $this->output("error", "Error, file with this name already exists !");
        }

        $config = Injector::resolve("Config");
        $config = $config->getConfig("application");
        $namespace = $config["controller_namespace"];

        $content = "<?php \n\n" .
                   "namespace " . rtrim($namespace, "\\") . ";\n\n" .
                   "class " . $this->payload . " \n" .
                   "{\n\n" .
                   "}";

        FileCreator::create($this->completePath, $content);

        $file = file_exists($this->completePath);
        if($file == true)
        {
            $this->output("success", "Success, " . $this->payload . " created !");
        } elseif($file == false) {
            $this->output("error", "Error, file was not created !");
        } else {
            echo "I have nothing to say...";
        }
    }
}

<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class CreateControllerCommand implements CommandInterface
{
    private $payload;

    private $path = __APP_ROOT__ . "/../src/Controllers";

    private $outputStyle = "\n\n";

    public function input($payload)
    {
        $this->payload = $payload;
        $this->process();
    }

    public function process()
    {
        $config = Injector::resolve("Config");
        $config = $config->getConfig("application");
        $namespace = $config["controller_namespace"];

        $content = "<?php \n\n" .
                   "namespace " . rtrim($namespace, "\\") . ";\n\n" .
                   "class " . $this->payload . "Controller \n" .
                   "{\n\n" .
                   "}";

        FileCreator::create($this->path . "/" . $this->payload . "Controller", $content);
        $this->output();
    }

    public function output()
    {
        $file = file_exists($this->path . "/" . $this->payload . "Controller.php");
        if($file == true)
        {
            echo $this->outputStyle . "\x1b[32m Success, file created !" . $this->outputStyle;
        } elseif($file == false) {
            echo $this->outputStyle . "\e[0;31m Error, file was not created !" . $this->outputStyle;
        } else {
            echo "I have nothing to say...";
        }
    }
}

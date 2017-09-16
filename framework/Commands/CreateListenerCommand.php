<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class CreateListenerCommand extends Command implements CommandInterface
{
    private $payload;

    private $path = __APP_ROOT__ . "/../src/Listeners";

    public function input($payload)
    {
        $this->payload = ucfirst($payload);
        $this->completePath = $this->path . "/" . $this->payload . "Listener.php";
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
        $namespace = $config["listener_namespace"];

        $content = "<?php \n\n" .
                   "namespace " . rtrim($namespace, "\\") . ";\n\n" .
                   "use Framework\Events\Subject;\n" .
                   "use Framework\Interfaces\ListenerInterface;\n\n" .
                   "class " . $this->payload . " implements ListenerInterface\n" .
                   "{\n" .
                       "\tpublic function listen(Subject \$subject)\n" .
                       "\t{\n\n" .
                       "\t}\n" .
                   "}";
        FileCreator::create($this->completePath, $content);

        $file = file_exists($this->completePath);
        if($file == true)
        {
            $this->output("success", "Success, " . $this->payload . "Listener created !");
        } elseif($file == false) {
            $this->output("error", "Error, file was not created !");
        } else {
            echo "I have nothing to say...";
        }
    }
}

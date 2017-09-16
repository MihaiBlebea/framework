<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class CreateEventCommand extends Command implements CommandInterface
{
    private $payload;

    private $path = __APP_ROOT__ . "/../src/Events";

    public function input($payload)
    {
        $this->payload = ucfirst($payload);
        $this->completePath = $this->path . "/" . $this->payload . "Event.php";
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
        $namespace = $config["event_namespace"];

        $content = "<?php \n\n" .
                   "namespace " . rtrim($namespace, "\\") . ";\n\n" .
                   "use Framework\Interfaces\EventInterface;\n" .
                   "use Framework\Events\Subject;\n\n" .
                   "class " . $this->payload . " implements EventInterface\n" .
                   "{\n" .
                       "\tprivate \$listeners = array();\n\n" .
                       "\tprivate \$subject;\n\n" .
                       "\tpublic function attach(\$listener)\n" .
                       "\t{\n" .
                           "\t\tarray_push(\$this->listeners, \$listener);\n" .
                           "\t\treturn \$this;\n" .
                       "\t}\n\n" .
                       "\tpublic function trigger(\$payload)\n" .
                       "\t{\n" .
                           "\t\t\$date = date('Y-m-d H:i:s');\n" .
                           "\t\t\$subject = new Subject(\$date, \$payload, __CLASS__);\n\n" .
                           "\t\t\$this->subject = \$subject;\n" .
                           "\t\t\$this->notify();\n" .
                       "\t}\n\n" .
                       "\tpublic function notify()\n" .
                       "\t{\n" .
                           "\t\tforeach(\$this->listeners as \$index => \$listener)\n" .
                           "\t\t{\n" .
                               "\t\t\t\$listener->listen(\$this->subject);\n" .
                           "\t\t}\n" .
                       "\t}\n" .
                   "}";
        FileCreator::create($this->completePath, $content);

        $file = file_exists($this->completePath);
        if($file == true)
        {
            $this->output("success", "Success, " . $this->payload . "Event created !");
        } elseif($file == false) {
            $this->output("error", "Error, file was not created !");
        } else {
            echo "I have nothing to say...";
        }
    }
}

<?php

namespace Framework\Commands;

use Framework\Interfaces\CommandInterface;
use Framework\Commands\Command;
use Framework\Injectables\Injector;
use Framework\Console\FileCreator;

class CreateEmailCommand extends Command implements CommandInterface
{
    private $payload;

    private $path = __APP_ROOT__ . "/../src/Emails";

    public function input($payload)
    {
        $this->payload = (strpos($payload, "Email") !== false) ? ucfirst($payload) : ucfirst($payload) . "Email";;
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
        $namespace = $config["email_namespace"];

        $content = "<?php \n\n" .
                   "namespace " . rtrim($namespace, "\\") . ";\n\n" .
                   "use Framework\Interfaces\EmailInterface;\n" .
                   "use Framework\Injectables\Injector;\n\n" .
                   "class " . $this->payload . " implements EmailInterface\n" .
                   "{\n" .
                       "\tpublic static function send(\$message)\n" .
                       "\t{\n" .
                           "\t\t\$email = Injector::resolve(\"Email\");\n" .
                           "\t\t\$email->subject(\"Subject here\");\n" .
                           "\t\t\$email->htmlBody(\"This is the message: \" . \$message);\n" .
                           "\t\t\$email->setAddress(\"send-to-this-email@email.com\");\n" .
                           "\t\t\$email->send();\n" .
                       "\t}\n" .
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

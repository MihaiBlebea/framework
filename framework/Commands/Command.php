<?php

namespace Framework\Commands;

class Command
{
    private $errorOutputStyle = "\n\n\e[0;31m";

    private $successOutputStyle = "\n\n\x1b[32m";

    private $normalOutputStyle = "\n\n";

    public function output($style, $content)
    {
        if($style == "success")
        {
            echo $this->successOutputStyle . $content . $this->successOutputStyle;
            die();
        } elseif($style == "error") {
            echo $this->errorOutputStyle . $content . $this->errorOutputStyle;
            die();
        } else {
            echo $this->normalOutputStyle . $content . $this->normalOutputStyle;
            die();
        }
    }
}

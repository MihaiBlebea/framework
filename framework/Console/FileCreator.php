<?php

namespace Framework\Console;

class FileCreator
{
    public static function create($file, $content)
    {
        $file = fopen($file . ".php", "w");
        fwrite($file, $content);
        fclose($file);
    }
}

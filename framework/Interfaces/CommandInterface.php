<?php

namespace Framework\Interfaces;

/**
 *
 */
interface CommandInterface
{
    public function input($payload);

    public function process();
}

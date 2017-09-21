<?php

namespace Framework\Interfaces;

interface MigrationInterface
{
    public function create();

    public function drop();
}

<?php

namespace Framework\Injectables;

use Framework\Injectables\Injector;
use Framework\Interfaces\ComponentInterface;
use Framework\Database\Migrator;
use Framework\Models\Migration;

class MigratorComponent extends Injector implements ComponentInterface
{
    public function boot()
    {
        self::register('Migrator', function() {
            $connector = Injector::resolve("Connector");
            $migration = new Migration();
            $migrator = new Migrator($connector, $migration);
            return $migrator;
        });
    }

    public function run($instance)
    {

    }
}

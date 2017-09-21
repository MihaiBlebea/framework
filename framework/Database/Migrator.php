<?php

namespace Framework\Database;

use Connector;
use Blueprint;
use Framework\Models\Migration;

class Migrator
{
    // holds instance of Connector
    public $connector;

    // Schema for building table
    public $blueprint;

    // The migration model
    public $migrationModel;

    public function __construct(Connector $connector, Migration $migration)
    {
        $this->$migrationModel = $migration;
        $this->connector = $connector->getConnector();
    }

    public function plan(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint->getPlan;
    }

    // Check if migrations table is set up or not, and if everything ok start creating the table,
    // If table is created, then create a new entry in the migrations table
    public function migrate()
    {
        $this->checkIfMigrationsTableExist();
        $result = $this->connector->execute($this->blueprint);

        if($result)
        {
            // update migration table
            $this->migrationModel->create([
                "table_name" => $migrationModel->getTable(),
                "schema"     => $this->blueprint,
                "status"     => 1
            ]);
        }
    }

    // Check if migration table is set up
    private function checkIfMigrationsTableExist()
    {
        $result = $migrationModel->where("table_name", "=", $this->blueprint->getTable())->selectOne();

        //Test response here
        dd($result);

        // if result is the expected model database then don't migrate this table anymore

        // if result is something like table not found then create the migrations table

        // if result is 0 entries found with that name then start migrating table and add new entry to the migrations database
        if($result == false)
        {
            $this->createMigrationsTable();
        }
    }

    // If migrations table is not set up, then set up the table here
    private function createMigrationsTable()
    {
        $schema = "CREATE " . $this->$migrationModel->getTable() . " (
                   ID int(11) AUTO_INCREMENT PRIMARY KEY,
                   table_name varchar(250) NOT NULL,
                   schema MEDIUMTEXT() NOT NULL),
                   status varchar(10) NOT NULL)";
        $this->connector->execute($schema);
    }

    public function drop($name)
    {
        $schema = "DROP DATABASE " . $name;
        $result = $this->connector->execute($schema);
        if($result)
        {
            $this->migrationModel->where("table_name", "=", $name)->delete();
        }
    }

    public function dropAll()
    {
        $migrations = $this->migrationModel->selectAll();
        foreach($migrations as $migration)
        {
            $this->drop($migration->table_name);
        }
    }
}

<?php

namespace Framework\Models;

use Model;

class Migration extends Model
{
    protected $table = 'migrations';
	public $tableKey = "id";
	public $id;
    public $table_name;
    public $schema;
    public $status;
}

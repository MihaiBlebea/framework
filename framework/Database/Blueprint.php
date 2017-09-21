<?php

namespace Framework\Database;

class Blueprint
{
    private $table;
    private $materials = array();
    private $plan;

    public function collect($table, $materials)
    {
        $this->table = $table;
        array_push($this->materials, $materials);
        return $this;
    }

    public function build()
    {
        $schema = "";
        foreach($this->materials as $index => $material)
        {
            if($index < count($this->materials) - 1)
            {
                $schema .= $material['name'] . ' ' . $material['type'] . ' ' . (empty($material['options']) ? '' : $material['options']) . ', ';
            } else {
                $schema .= $material['name'] . ' ' . $material['type'] . ' ' . (empty($material['options']) ? '' : $material['options']);
            }
        }
        $this->plan = "CREATE TABLE " . $this->table . " (" . $schema . ")";
    }

    public getPlan()
    {
        return $this->plan;
    }

    public getTable()
    {
        return $this->table;
    }
}

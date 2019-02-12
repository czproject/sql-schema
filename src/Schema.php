<?php

namespace CzProject\SqlSchema;

use CzProject\SqlSchema\Exceptions\DuplicateException;

class Schema
{
    /** @var array  [name => Table] */
    private $tables = array();

    /**
    * @param  string|Table
    * @return Table
    */
    public function addTable($name)
    {
        $table = null;

        if ($name instanceof Table) {
            $table = $name;
            $name = $table->getName();
        } else {
            $table = new Table($name);
        }

        if (isset($this->tables[$name])) {
            throw new DuplicateException("Table '$name' already exists.");
        }

        return $this->tables[$name] = $table;
    }

    /**
    * @param  string
    * @return Table|NULL
    */
    public function getTable($name)
    {
        if (isset($this->tables[$name])) {
            return $this->tables[$name];
        }

        return null;
    }

    /**
    * @return Table[]
    */
    public function getTables()
    {
        return $this->tables;
    }
}

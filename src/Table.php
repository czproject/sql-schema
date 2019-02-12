<?php

namespace CzProject\SqlSchema;

use CzProject\SqlSchema\Exceptions\DuplicateException;
use CzProject\SqlSchema\Exceptions\EmptyException;

class Table
{
    /**
    * @var string
    */
    private $name;

    /**
    * @var string|NULL
    */
    private $comment;

    /**
    * @var array  [name => Column]
    */
    private $columns = array();

    /**
    * @var array  [name => Index]
    */
    private $indexes = array();

    /**
    * @var array  [name => ForeignKey]
    */
    private $foreignKeys = array();

    /**
    * @var array  [name => value]
    */
    private $options = array();

    /**
    * @param string
    */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * @param  string|NULL
    * @return self
    */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
    * @return string|NULL
    */
    public function getComment()
    {
        return $this->comment;
    }

    /**
    * @param  string
    * @param  string
    * @return self
    */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
    * @return array
    */
    public function getOptions()
    {
        return $this->options;
    }

    /**
    * @param  string|Column
    * @param  string|NULL
    * @param  array|string|NULL
    * @param  array OPTION => NULL
    * @return Column
    */
    public function addColumn($name, $type = null, $parameters = null, array $options = array())
    {
        $column = null;

        if ($name instanceof Column) {
            $column = $name;
            $name = $column->getName();
        } else {
            $column = new Column($name, $type, $parameters, $options);
        }

        if (isset($this->columns[$name])) {
            throw new DuplicateException("Column '$name' in table '{$this->getName()}' already exists.");
        }

        $this->columns[$name] = $column;
        return $this;
    }

    /**
    * @param  string
    * @return Column|NULL
    */
    public function getColumn($name)
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name];
        }
        return null;
    }

    /**
    * @return Column[]
    */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
    * @param  string|Index|NULL
    * @param  string[]|string
    * @param  string
    * @return Index
    */
    public function addIndex($name, $columns = array(), $type = Index::TYPE_INDEX)
    {
        $index = null;

        if ($name instanceof Index) {
            $index = $name;
            $name = $index->getName();
        } else {
            $index = new Index($name, $columns, $type);
            $name = $index->getName();
        }

        if (isset($this->indexes[$name])) {
            throw new DuplicateException("Index '$name' in table '{$this->getName()}' already exists.");
        }

        $this->indexes[$name] = $index;
        return $this;
    }

    /**
    * @param  string
    * @return Index|NULL
    */
    public function getIndex($name)
    {
        if (isset($this->indexes[$name])) {
            return $this->indexes[$name];
        }
        return null;
    }

    /**
    * @return Index[]
    */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
    * @param  string|ForeignKey
    * @param  string[]|string
    * @param  string
    * @param  string[]|string
    * @return ForeignKey
    */
    public function addForeignKey($name, $columns = array(), $targetTable = null, $targetColumns = array())
    {
        $foreignKey = null;

        if ($name instanceof ForeignKey) {
            $foreignKey = $name;
            $name = $foreignKey->getName();
        } else {
            $foreignKey = new ForeignKey($name, $columns, $targetTable, $targetColumns);
            $name = $foreignKey->getName();
        }

        if (isset($this->foreignKeys[$name])) {
            throw new DuplicateException("Foreign key '$name' in table '{$this->getName()}' already exists.");
        }

        $this->foreignKeys[$name] = $foreignKey;
        return $this;
    }

    /**
    * @param  string
    * @return ForeignKey|NULL
    */
    public function getForeignKey($name)
    {
        if (isset($this->foreignKeys[$name])) {
            return $this->foreignKeys[$name];
        }
        return null;
    }

    /**
    * @return ForeignKey[]
    */
    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }

    /**
    * @throws Exception
    * @return void
    */
    public function validate()
    {
        $tableName = $this->getName();

        if (empty($this->columns)) {
            throw new EmptyException("Table '$tableName' hasn't columns.");
        }

        $hasPrimaryIndex = false;

        foreach ($this->getIndexes() as $index) {
            if ($index->getType() === Index::TYPE_PRIMARY) {
                if ($hasPrimaryIndex) {
                    throw new DuplicateException("Duplicated primary index in table '$tableName'.");
                }
                $hasPrimaryIndex = true;
            }
        }
    }

    /**
    * @return string
    */
    public function showCreate()
    {
        $out = "CREATE TABLE " . $this->getName() . " (\n";
        $columns = [];
        foreach ($this->getColumns() as $k => $column) {
            $col = [];

            $col[] = '`'.$column->getName() . '`';
            $col[] = $column->getType().
                    (count($column->getParameters()) ? '('.implode(' ', $column->getParameters()).')' : '');
            $col[] = implode(' ', array_keys($column->getOptions()));
            $col[] = ($column->isNullable() == false ? 'NOT NULL' : '');
            $col[] = ($column->getDefaultValue() != null ? 'DEFAULT \''.$column->getDefaultValue().'\'' : '');
            $col[] = ($column->isAutoIncrement() == true ? 'AUTO_INCREMENT' : '');
            $col[] = (trim($column->getComment()) != '' ? 'COMMENT \''.$column->getComment().'\'' : '');

            $columns[] = implode(' ', array_filter($col));
        }

        $indexes = [];
        foreach ($this->getIndexes() as $key => $index) {
            $idx = $index->getType() ;
            $idx .= ' ' . ($index->getType() == 'PRIMARY' ? ' KEY' : '') ;
            $idx .= ' ' . ($index->getName() != null ? '`'.$index->getName().'`' : '');
            $indexcols = [];
            foreach ($index->getColumns() as $ick => $indexcol) {
                $indexcols[] = '`'.$indexcol->getName().'`';
            }
            $idx .= '('. implode(', ', $indexcols) .')';
            $indexes[] = $idx;
        }

        $foreigns = [];
        foreach ($this->getForeignKeys() as $key => $foreign) {
            $idx = 'CONSTRAINT ' . $foreign->getName() . ' FOREIGN KEY';
            $idx .= ' ('. implode(', ', $foreign->getColumns()) .')';
            $idx .= ' REFERENCES ' . $foreign->getTargetTable() . ' ';
            $idx .= '('. implode(', ', $foreign->getTargetColumns()) .')';

            if ($foreign->getOnUpdateAction()) {
                $idx .= ' ON UPDATE ' . $foreign->getOnUpdateAction() . ' ';
            }
            if ($foreign->getOnDeleteAction()) {
                $idx .= ' ON DELETE ' . $foreign->getOnUpdateAction() . ' ';
            }

            $foreigns[] = $idx;
        }

        $out .= implode(",\n", $columns);
        $out .= ((!empty($indexes) || !empty($foreigns)) ? ',':'')."\n";

        if (!empty($indexes)) {
            $out .= implode(",\n", $indexes);
        }
        $out .= ((!empty($foreigns)) ? ',':'')."\n";

        if (!empty($foreigns)) {
            $out .= implode(",\n", $foreigns);
        }
        $out .= ((!empty($foreigns)) ? "\n":'');

        $out .= ");";
        return $out;
    }
}

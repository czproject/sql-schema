<?php

namespace CzProject\SqlSchema;

use CzProject\SqlSchema\Exceptions\OutOfRangeException;

class Index
{
    const TYPE_INDEX = 'INDEX';
    const TYPE_PRIMARY = 'PRIMARY';
    const TYPE_UNIQUE = 'UNIQUE';
    const TYPE_FULLTEXT = 'FULLTEXT';

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var IndexColumn[] */
    private $columns = array();

    /**
     * @param  string
     * @param  string[]|string
     * @param  string
     */
    public function __construct($name, $columns = array(), $type = self::TYPE_INDEX)
    {
        $this->name = $name;
        $this->setType($type);

        if (!is_array($columns)) {
            $columns = array($columns);
        }

        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string
     * @return self
     */
    public function setType($type)
    {
        $type = (string) $type;
        $exists = $type === self::TYPE_INDEX
        || $type === self::TYPE_PRIMARY
        || $type === self::TYPE_UNIQUE
        || $type === self::TYPE_FULLTEXT;

        if (!$exists) {
            throw new OutOfRangeException("Index type '$type' not found.");
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  IndexColumn|string
     * @return IndexColumn
     */
    public function addColumn($column)
    {
        if (!($column instanceof IndexColumn)) {
            $column = new IndexColumn($column);
        }

        $this->columns[] = $column;
        return $this;
    }

    /**
     * @return IndexColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }
}

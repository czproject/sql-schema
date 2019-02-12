<?php

namespace CzProject\SqlSchema;

use CzProject\SqlSchema\Exceptions\OutOfRangeException;

class IndexColumn
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    /** @var string */
    private $name;

    /** @var string */
    private $order;

    /** @var int|NULL */
    private $length;

    /**
     * @param  string
     * @param  string
     * @param  int|NULL
     */
    public function __construct($name, $order = self::ASC, $length = null)
    {
        $this->setName($name);
        $this->setOrder($order);
        $this->setLength($length);
    }

    /**
     * @param  string
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
    public function setOrder($order)
    {
        $order = (string) $order;

        if ($order !== self::ASC && $order !== self::DESC) {
            throw new OutOfRangeException("Order type '$order' not found.");
        }

        $this->order = $order;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param  int|NULL
     * @return self
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return int|NULL
     */
    public function getLength()
    {
        return $this->length;
    }
}

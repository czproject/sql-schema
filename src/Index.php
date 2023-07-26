<?php

	namespace CzProject\SqlSchema;


	class Index
	{
		const TYPE_INDEX = 'INDEX';
		const TYPE_PRIMARY = 'PRIMARY';
		const TYPE_UNIQUE = 'UNIQUE';
		const TYPE_FULLTEXT = 'FULLTEXT';

		/** @var string|NULL */
		private $name;

		/** @var string */
		private $type;

		/** @var IndexColumn[] */
		private $columns = [];


		/**
		 * @param  string|NULL $name
		 * @param  string[]|string $columns
		 * @param  string $type
		 */
		public function __construct($name, $columns = [], $type = self::TYPE_INDEX)
		{
			$this->name = $name;
			$this->setType($type);

			if (!is_array($columns)) {
				$columns = [$columns];
			}

			foreach ($columns as $column) {
				$this->addColumn($column);
			}
		}


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @param  string $type
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
		 * @param  IndexColumn|string $column
		 * @return IndexColumn
		 */
		public function addColumn($column)
		{
			if (!($column instanceof IndexColumn)) {
				$column = new IndexColumn($column);
			}

			return $this->columns[] = $column;
		}


		/**
		 * @return IndexColumn[]
		 */
		public function getColumns()
		{
			return $this->columns;
		}
	}

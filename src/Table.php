<?php

	namespace CzProject\SqlSchema;


	class Table
	{
		/** @var string */
		private $name;

		/** @var string|NULL */
		private $comment;

		/** @var array<string, Column>  [name => Column] */
		private $columns = [];

		/** @var array<string, Index>  [name => Index] */
		private $indexes = [];

		/** @var array<string, ForeignKey>  [name => ForeignKey] */
		private $foreignKeys = [];

		/** @var array<string, string>  [name => value] */
		private $options = [];


		/**
		 * @param  string $name
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
		 * @param  string|NULL $comment
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
		 * @param  string $name
		 * @param  string $value
		 * @return self
		 */
		public function setOption($name, $value)
		{
			$this->options[$name] = $value;
			return $this;
		}


		/**
		 * @return array<string, string>
		 */
		public function getOptions()
		{
			return $this->options;
		}


		/**
		 * @param  string|Column $name
		 * @param  string|NULL $type
		 * @param  array<scalar>|NULL $parameters
		 * @param  array<string, string> $options OPTION => NULL
		 * @return Column
		 */
		public function addColumn($name, $type = NULL, array $parameters = NULL, array $options = [])
		{
			$column = NULL;

			if ($name instanceof Column) {
				$column = $name;
				$name = $column->getName();

			} else {
				$column = new Column($name, $type, $parameters, $options);
			}

			if (isset($this->columns[$name])) {
				throw new DuplicateException("Column '$name' in table '{$this->getName()}' already exists.");
			}

			return $this->columns[$name] = $column;
		}


		/**
		 * @param  string $name
		 * @return Column|NULL
		 */
		public function getColumn($name)
		{
			if (isset($this->columns[$name])) {
				return $this->columns[$name];
			}
			return NULL;
		}


		/**
		 * @return Column[]
		 */
		public function getColumns()
		{
			return $this->columns;
		}


		/**
		 * @param  string|Index $name
		 * @param  string[]|string $columns
		 * @param  string $type
		 * @return Index
		 */
		public function addIndex($name, $columns = [], $type = Index::TYPE_INDEX)
		{
			$index = NULL;

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

			return $this->indexes[$name] = $index;
		}


		/**
		 * @param  string $name
		 * @return Index|NULL
		 */
		public function getIndex($name)
		{
			if (isset($this->indexes[$name])) {
				return $this->indexes[$name];
			}
			return NULL;
		}


		/**
		 * @return Index[]
		 */
		public function getIndexes()
		{
			return $this->indexes;
		}


		/**
		 * @param  string|ForeignKey $name
		 * @param  string[]|string $columns
		 * @param  string $targetTable
		 * @param  string[]|string $targetColumns
		 * @return ForeignKey
		 */
		public function addForeignKey($name, $columns = [], $targetTable = NULL, $targetColumns = [])
		{
			$foreignKey = NULL;

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

			return $this->foreignKeys[$name] = $foreignKey;
		}


		/**
		 * @param  string $name
		 * @return ForeignKey|NULL
		 */
		public function getForeignKey($name)
		{
			if (isset($this->foreignKeys[$name])) {
				return $this->foreignKeys[$name];
			}
			return NULL;
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

			$hasPrimaryIndex = FALSE;

			foreach ($this->getIndexes() as $index) {
				if ($index->getType() === Index::TYPE_PRIMARY) {
					if ($hasPrimaryIndex) {
						throw new DuplicateException("Duplicated primary index in table '$tableName'.");
					}
					$hasPrimaryIndex = TRUE;
				}
			}
		}
	}

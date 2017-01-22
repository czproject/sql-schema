<?php

	namespace CzProject\SqlSchema;


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
			$table = NULL;

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

			return NULL;
		}


		/**
		 * @return Table[]
		 */
		public function getTables()
		{
			return $this->tables;
		}
	}

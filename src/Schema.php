<?php

	declare(strict_types=1);

	namespace CzProject\SqlSchema;


	class Schema
	{
		/** @var array<string, Table>  [name => Table] */
		private $tables = [];


		/**
		 * @param  string|Table $name
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
		 * @param  string $name
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

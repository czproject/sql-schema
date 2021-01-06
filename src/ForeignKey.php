<?php

	namespace CzProject\SqlSchema;


	class ForeignKey
	{
		const ACTION_RESTRICT = 'RESTRICT';
		const ACTION_NO_ACTION = 'NO ACTION';
		const ACTION_CASCADE = 'CASCADE';
		const ACTION_SET_NULL = 'SET NULL';

		/** @var string */
		private $name;

		/** @var string[] */
		private $columns = [];

		/** @var string */
		private $targetTable;

		/** @var string[] */
		private $targetColumns;

		/** @var string */
		private $onUpdateAction = self::ACTION_RESTRICT;

		/** @var string */
		private $onDeleteAction = self::ACTION_RESTRICT;


		/**
		 * @param  string
		 * @param  string[]|string
		 * @param  string
		 * @param  string[]|string
		 */
		public function __construct($name, $columns, $targetTable, $targetColumns)
		{
			$this->name = $name;
			$this->setTargetTable($targetTable);

			if (!is_array($columns)) {
				$columns = [$columns];
			}

			foreach ($columns as $column) {
				$this->addColumn($column);
			}

			if (!is_array($targetColumns)) {
				$targetColumns = [$targetColumns];
			}

			foreach ($targetColumns as $targetColumn) {
				$this->addTargetColumn($targetColumn);
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
		public function addColumn($column)
		{
			return $this->columns[] = $column;
		}


		/**
		 * @return string[]
		 */
		public function getColumns()
		{
			return $this->columns;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setTargetTable($targetTable)
		{
			$this->targetTable = $targetTable;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getTargetTable()
		{
			return $this->targetTable;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function addTargetColumn($targetColumn)
		{
			return $this->targetColumns[] = $targetColumn;
		}


		/**
		 * @return string[]
		 */
		public function getTargetColumns()
		{
			return $this->targetColumns;
		}


		/**
		 * @param  int
		 * @return self
		 */
		public function setOnUpdateAction($onUpdateAction)
		{
			if (!$this->validateAction($onUpdateAction)) {
				throw new OutOfRangeException("Action '$onUpdateAction' is invalid.");
			}

			$this->onUpdateAction = $onUpdateAction;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getOnUpdateAction()
		{
			return $this->onUpdateAction;
		}


		/**
		 * @param  int
		 * @return self
		 */
		public function setOnDeleteAction($onDeleteAction)
		{
			if (!$this->validateAction($onDeleteAction)) {
				throw new OutOfRangeException("Action '$onDeleteAction' is invalid.");
			}

			$this->onDeleteAction = $onDeleteAction;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getOnDeleteAction()
		{
			return $this->onDeleteAction;
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private function validateAction($action)
		{
			return $action === self::ACTION_RESTRICT
				|| $action === self::ACTION_NO_ACTION
				|| $action === self::ACTION_CASCADE
				|| $action === self::ACTION_SET_NULL;
		}
	}

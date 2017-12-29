<?php

	namespace CzProject\SqlSchema;


	class Column
	{
		const OPTION_UNSIGNED = 'UNSIGNED';
		const OPTION_ZEROFILL = 'ZEROFILL';

		/** @var string */
		private $name;

		/** @var string */
		private $type;

		/** @var array */
		private $parameters = array();

		/** @var array */
		private $options = array();

		/** @var bool */
		private $nullable = FALSE;

		/** @var bool */
		private $autoIncrement = FALSE;

		/** @var scalar|NULL */
		private $defaultValue;

		/** @var string|NULL */
		private $comment;


		/**
		 * @param  string
		 * @param  string|NULL
		 * @param  array|string|NULL
		 * @param  array  [OPTION => VALUE, OPTION2]
		 */
		public function __construct($name, $type, array $parameters = NULL, array $options = array())
		{
			$this->name = $name;
			$this->setType($type);
			$this->setParameters($parameters);
			$this->setOptions($options);
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
		 * @param  string|array|NULL
		 * @return self
		 */
		public function setParameters($parameters)
		{
			if ($parameters === NULL) {
				$parameters = array();

			} elseif (!is_array($parameters)) {
				$parameters = array($parameters);
			}

			$this->parameters = $parameters;
			return $this;
		}


		/**
		 * @return array
		 */
		public function getParameters()
		{
			return $this->parameters;
		}


		/**
		 * @param  string
		 * @param  scalar|NULL
		 * @return self
		 */
		public function addOption($option, $value = NULL)
		{
			$this->options[$option] = $value;
			return $this;
		}


		/**
		 * @param  array
		 * @return self
		 */
		public function setOptions(array $options)
		{
			$this->options = array();

			foreach ($options as $k => $v) {
				if (is_int($k)) {
					$this->options[$v] = NULL;

				} else {
					$this->options[$k] = $v;
				}
			}

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
		 * @param  bool
		 * @return self
		 */
		public function setNullable($nullable = TRUE)
		{
			$this->nullable = $nullable;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function isNullable()
		{
			return $this->nullable;
		}


		/**
		 * @param  bool
		 * @return self
		 */
		public function setAutoIncrement($autoIncrement = TRUE)
		{
			$this->autoIncrement = $autoIncrement;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function isAutoIncrement()
		{
			return $this->autoIncrement;
		}


		/**
		 * @param  scalar|NULL
		 * @return self
		 */
		public function setDefaultValue($defaultValue)
		{
			$this->defaultValue = $defaultValue;
			return $this;
		}


		/**
		 * @return scalar|NULL
		 */
		public function getDefaultValue()
		{
			return $this->defaultValue;
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
	}

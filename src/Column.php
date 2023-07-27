<?php

	namespace CzProject\SqlSchema;


	class Column
	{
		const OPTION_UNSIGNED = 'UNSIGNED';
		const OPTION_ZEROFILL = 'ZEROFILL';

		/** @var string */
		private $name;

		/** @var string|NULL */
		private $type;

		/** @var array<scalar> */
		private $parameters = [];

		/** @var array<string, scalar|NULL> */
		private $options = [];

		/** @var bool */
		private $nullable = FALSE;

		/** @var bool */
		private $autoIncrement = FALSE;

		/** @var scalar|NULL */
		private $defaultValue;

		/** @var string|NULL */
		private $comment;


		/**
		 * @param  string $name
		 * @param  string|NULL $type
		 * @param  array<scalar>|NULL $parameters
		 * @param  array<string|int, scalar|NULL> $options  [OPTION => VALUE, OPTION2]
		 */
		public function __construct($name, $type, array $parameters = NULL, array $options = [])
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
		 * @param  string|NULL $type
		 * @return $this
		 */
		public function setType($type)
		{
			$this->type = $type;
			return $this;
		}


		/**
		 * @return string|NULL
		 */
		public function getType()
		{
			return $this->type;
		}


		/**
		 * @param  scalar|array<scalar>|NULL $parameters
		 * @return $this
		 */
		public function setParameters($parameters)
		{
			if ($parameters === NULL) {
				$parameters = [];

			} elseif (!is_array($parameters)) {
				$parameters = [$parameters];
			}

			$this->parameters = $parameters;
			return $this;
		}


		/**
		 * @return array<scalar>
		 */
		public function getParameters()
		{
			return $this->parameters;
		}


		/**
		 * @param  string $option
		 * @param  scalar|NULL $value
		 * @return $this
		 */
		public function addOption($option, $value = NULL)
		{
			$this->options[$option] = $value;
			return $this;
		}


		/**
		 * @param  array<string|int, scalar|NULL> $options
		 * @return $this
		 */
		public function setOptions(array $options)
		{
			$this->options = [];

			foreach ($options as $k => $v) {
				if (is_int($k)) {
					$this->options[(string) $v] = NULL;

				} else {
					$this->options[$k] = $v;
				}
			}

			return $this;
		}


		/**
		 * @return array<string, scalar|NULL>
		 */
		public function getOptions()
		{
			return $this->options;
		}


		/**
		 * @param  string $name
		 * @return bool
		 */
		public function hasOption($name)
		{
			return array_key_exists($name, $this->options);
		}


		/**
		 * @param  bool $nullable
		 * @return $this
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
		 * @param  bool $autoIncrement
		 * @return $this
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
		 * @param  scalar|NULL $defaultValue
		 * @return $this
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
		 * @param  string|NULL $comment
		 * @return $this
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

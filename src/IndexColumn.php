<?php

	declare(strict_types=1);

	namespace CzProject\SqlSchema;


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
		 * @param  string $name
		 * @param  string $order
		 * @param  int|NULL $length
		 */
		public function __construct($name, $order = self::ASC, $length = NULL)
		{
			$this->setName($name);
			$this->setOrder($order);
			$this->setLength($length);
		}


		/**
		 * @param  string $name
		 * @return $this
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
		 * @param  string $order
		 * @return $this
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
		 * @param  int|NULL $length
		 * @return $this
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

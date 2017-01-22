<?php

use CzProject\SqlSchema\Schema;
use CzProject\SqlSchema\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$schema = new Schema;
	Assert::same(array(), $schema->getTables());
	Assert::null($schema->getTable('book'));

	$schema->addTable('book');

	Assert::notSame(NULL, $schema->getTable('book'));
});


test(function () {
	Assert::exception(function () {
		$schema = new Schema;
		$schema->addTable('book');
		$schema->addTable('book');

	}, 'CzProject\\SqlSchema\\DuplicateException', "Table 'book' already exists.");


	Assert::exception(function () {
		$schema = new Schema;
		$schema->addTable('book');

		$book = new Table('book');
		$schema->addTable($book);

	}, 'CzProject\\SqlSchema\\DuplicateException', "Table 'book' already exists.");
});

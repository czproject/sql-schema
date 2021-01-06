<?php

use CzProject\SqlSchema\Index;
use CzProject\SqlSchema\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->validate();
	}, CzProject\SqlSchema\EmptyException::class, "Table 'book' hasn't columns.");
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addColumn('id', 'INT');
		$table->addIndex('id', [], Index::TYPE_PRIMARY);
		$table->addIndex('primary', [], Index::TYPE_PRIMARY);
		$table->validate();
	}, CzProject\SqlSchema\DuplicateException::class, "Duplicated primary index in table 'book'.");
});


test(function () {
	$table = new Table('book');
	$table->addColumn('id', 'INT');
	Assert::null($table->validate());
});

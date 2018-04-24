<?php

use CzProject\SqlSchema\Index;
use CzProject\SqlSchema\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->validate();
	}, 'CzProject\\SqlSchema\\EmptyException', "Table 'book' hasn't columns.");
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addColumn('id', 'INT');
		$table->addIndex('id', array(), Index::TYPE_PRIMARY);
		$table->addIndex('primary', array(), Index::TYPE_PRIMARY);
		$table->validate();
	}, 'CzProject\\SqlSchema\\DuplicateException', "Duplicated primary index in table 'book'.");
});


test(function () {
	$table = new Table('book');
	$table->addColumn('id', 'INT');
	Assert::null($table->validate());
});

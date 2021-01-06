<?php

use CzProject\SqlSchema\Column;
use CzProject\SqlSchema\ForeignKey;
use CzProject\SqlSchema\Index;
use CzProject\SqlSchema\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$table = new Table('book');
	Assert::same('book', $table->getName());
	Assert::same([], $table->getColumns());
	Assert::same([], $table->getIndexes());
	Assert::null($table->getComment());
	Assert::same([], $table->getOptions());
	Assert::same([], $table->getForeignKeys());
});


test(function () {
	$table = new Table('book');
	$table->setComment('table comment');
	$table->setOption('ENGINE', 'InnoDB');

	Assert::same('table comment', $table->getComment());
	Assert::same([
		'ENGINE' => 'InnoDB',
	], $table->getOptions());
});


test(function () {
	$table = new Table('book');
	$table->addForeignKey(NULL, 'author_id', 'author', 'id');

	Assert::same([
		'CONSTRAINT  FOREIGN KEY (author_id) REFERENCES author (id)',
	], format($table->getForeignKeys()));
});


test(function () {
	$table = new Table('book');

	Assert::null($table->getColumn('id'));
	Assert::null($table->getIndex('id'));
	Assert::null($table->getForeignKey('author_id'));

	$table->addColumn('id', 'INT');
	$table->addIndex('id');
	$table->addForeignKey('author_id');

	Assert::notSame(NULL, $table->getColumn('id'));
	Assert::notSame(NULL, $table->getIndex('id'));
	Assert::notSame(NULL, $table->getForeignKey('author_id'));
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addColumn('id', 'INT');
		$table->addColumn('id', 'TINYINT');

	}, CzProject\SqlSchema\DuplicateException::class, "Column 'id' in table 'book' already exists.");

	Assert::exception(function () {
		$table = new Table('book');
		$table->addColumn('id', 'INT');
		$table->addColumn(new Column('id', 'INT'));

	}, CzProject\SqlSchema\DuplicateException::class, "Column 'id' in table 'book' already exists.");
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addIndex('id', Index::TYPE_INDEX);
		$table->addIndex('id', Index::TYPE_INDEX);

	}, CzProject\SqlSchema\DuplicateException::class, "Index 'id' in table 'book' already exists.");

	Assert::exception(function () {
		$table = new Table('book');
		$table->addIndex('id', Index::TYPE_INDEX);
		$table->addIndex(new Index('id'));

	}, CzProject\SqlSchema\DuplicateException::class, "Index 'id' in table 'book' already exists.");
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addForeignKey('author_id');
		$table->addForeignKey('author_id');

	}, CzProject\SqlSchema\DuplicateException::class, "Foreign key 'author_id' in table 'book' already exists.");

	Assert::exception(function () {
		$table = new Table('book');
		$table->addForeignKey('author_id');
		$table->addForeignKey(new ForeignKey('author_id', 'author_id', 'author', 'id'));

	}, CzProject\SqlSchema\DuplicateException::class, "Foreign key 'author_id' in table 'book' already exists.");
});

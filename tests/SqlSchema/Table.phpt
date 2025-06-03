<?php

declare(strict_types=1);

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
	$table->addForeignKey('author_id', [], 'author');

	Assert::notSame(NULL, $table->getColumn('id'));
	Assert::notSame(NULL, $table->getIndex('id'));
	Assert::notSame(NULL, $table->getForeignKey('author_id'));
});


test(function () {
	$table = new Table('book');

	$table->addColumn('id', NULL); // no specified type
	Assert::notSame(NULL, $table->getColumn('id'));
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
		$table->removeColumn('id');

	}, CzProject\SqlSchema\MissingException::class, "Column 'id' in table 'book' not exists.");

	$table = new Table('book');
	$column = $table->addColumn('id', 'INT');
	Assert::same(1, count($table->getColumns()));

	$table->removeColumn($column);
	Assert::same(0, count($table->getColumns()));
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
		$table->removeIndex('id');

	}, CzProject\SqlSchema\MissingException::class, "Index 'id' in table 'book' not exists.");

	$table = new Table('book');
	$index = $table->addIndex('id', Index::TYPE_INDEX);
	Assert::same(1, count($table->getIndexes()));

	$table->removeIndex($index);
	Assert::same(0, count($table->getIndexes()));
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->addForeignKey('author_id', [], 'author');
		$table->addForeignKey('author_id', [], 'author');

	}, CzProject\SqlSchema\DuplicateException::class, "Foreign key 'author_id' in table 'book' already exists.");

	Assert::exception(function () {
		$table = new Table('book');
		$table->addForeignKey('author_id', [], 'author');
		$table->addForeignKey(new ForeignKey('author_id', 'author_id', 'author', 'id'));

	}, CzProject\SqlSchema\DuplicateException::class, "Foreign key 'author_id' in table 'book' already exists.");
});


test(function () {
	Assert::exception(function () {
		$table = new Table('book');
		$table->removeForeignKey('author_id');

	}, CzProject\SqlSchema\MissingException::class, "Foreign key 'author_id' in table 'book' not exists.");

	$table = new Table('book');
	$foreignKey = $table->addForeignKey('author_id', [], 'author');
	Assert::same(1, count($table->getForeignKeys()));

	$table->removeForeignKey($foreignKey);
	Assert::same(0, count($table->getForeignKeys()));
});

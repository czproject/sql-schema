<?php

use CzProject\SqlSchema\Column;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$column = new CzProject\SqlSchema\Column('id', 'INT');

	Assert::same('id', $column->getName());
	Assert::same('INT', $column->getType());
	Assert::false($column->isNullable());
	Assert::false($column->isAutoIncrement());
	Assert::same(array(), $column->getOptions());
	Assert::same(array(), $column->getParameters());
	Assert::null($column->getComment());
	Assert::null($column->getDefaultValue());
});


test(function () {
	$column = new CzProject\SqlSchema\Column('id', 'INT');
	$column->setNullable();
	$column->setAutoIncrement();
	$column->setComment('column comment');
	$column->setParameters(11);
	$column->setOptions(array(
		'ZEROFILL',
		'CHARACTER SET' => 'utf8',
	));
	$column->addOption('UNSIGNED');
	$column->addOption('COLLATE', 'latin1_swedish_ci');
	$column->setDefaultValue(123);

	Assert::same('id', $column->getName());
	Assert::same('INT', $column->getType());
	Assert::true($column->isNullable());
	Assert::true($column->isAutoIncrement());
	Assert::same(array(
		'ZEROFILL' => NULL,
		'CHARACTER SET' => 'utf8',
		'UNSIGNED' => NULL,
		'COLLATE' => 'latin1_swedish_ci',
	), $column->getOptions());
	Assert::same(array(11), $column->getParameters());
	Assert::same('column comment', $column->getComment());
	Assert::same(123, $column->getDefaultValue());
});

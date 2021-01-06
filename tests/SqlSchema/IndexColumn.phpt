<?php

use CzProject\SqlSchema\IndexColumn;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$column = new CzProject\SqlSchema\IndexColumn('id', 'DESC');

	Assert::same('id', $column->getName());
	Assert::same('DESC', $column->getOrder());
	Assert::null($column->getLength());

	$column->setLength(200);
	Assert::same(200, $column->getLength());
});


test(function () {
	Assert::exception(function () {
		$column = new CzProject\SqlSchema\IndexColumn('id', 'BLA');
	}, CzProject\SqlSchema\OutOfRangeException::class, "Order type 'BLA' not found.");
});

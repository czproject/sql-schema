<?php

declare(strict_types=1);

use CzProject\SqlSchema\ForeignKey;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$foreignKey = new ForeignKey('fk_name', 'author_id', 'author', 'id');

	Assert::same('fk_name', $foreignKey->getName());
	Assert::same(['author_id'], $foreignKey->getColumns());
	Assert::same('author', $foreignKey->getTargetTable());
	Assert::same(['id'], $foreignKey->getTargetColumns());
	Assert::same($foreignKey::ACTION_RESTRICT, $foreignKey->getOnUpdateAction());
	Assert::same($foreignKey::ACTION_RESTRICT, $foreignKey->getOnDeleteAction());
});


test(function () {
	$foreignKey = new ForeignKey('fk_name', 'author_id', 'author', 'id');
	$foreignKey->setOnUpdateAction(ForeignKey::ACTION_NO_ACTION);
	$foreignKey->setOnDeleteAction(ForeignKey::ACTION_SET_NULL);

	Assert::same($foreignKey::ACTION_NO_ACTION, $foreignKey->getOnUpdateAction());
	Assert::same($foreignKey::ACTION_SET_NULL, $foreignKey->getOnDeleteAction());
});


test(function () {
	Assert::exception(function () {
		$foreignKey = new ForeignKey('fk_name', 'author_id', 'author', 'id');
		$foreignKey->setOnUpdateAction('BLAH');
	}, CzProject\SqlSchema\OutOfRangeException::class, "Action 'BLAH' is invalid.");

	Assert::exception(function () {
		$foreignKey = new ForeignKey('fk_name', 'author_id', 'author', 'id');
		$foreignKey->setOnDeleteAction('BLAH');
	}, CzProject\SqlSchema\OutOfRangeException::class, "Action 'BLAH' is invalid.");
});

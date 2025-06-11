<?php

declare(strict_types=1);

use CzProject\SqlSchema;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


/**
 * @return void
 */
function test(callable $cb)
{
	$cb();
}


/**
 * @return array<string, array<string>>
 */
function formatSchema(SqlSchema\Schema $obj): array
{
	$output = [];

	foreach ($obj->getTables() as $table) {
		$output[$table->getName()] = formatTable($table);
	}

	return $output;
}


/**
 * @return array<string>
 */
function formatTable(SqlSchema\Table $obj): array
{
	$output = [];

	foreach ($obj->getColumns() as $column) {
		$output[] = formatColumn($column);
	}

	foreach ($obj->getIndexes() as $index) {
		$output[] = formatIndex($index);
	}

	return $output;
}


function formatColumn(SqlSchema\Column $obj): string
{
	$output = $obj->getName();
	$output .= ' ' . $obj->getType();
	$parameters = $obj->getParameters();

	if (!empty($parameters)) {
		$output .= '(' . implode(', ', $parameters) . ')';
	}

	foreach ($obj->getOptions() as $option => $value) {
		$output .= ' ' . $option;

		if (isset($value)) {
			$output .= ' = ' . $value;
		}
	}

	$output .= $obj->isNullable() ? ' NULL' : ' NOT NULL';
	$output .= $obj->isAutoIncrement() ? ' AUTO_INCREMENT' : '';

	$comment = $obj->getComment();
	$output .= isset($comment) ? (' COMMENT ' . $comment) : '';
	return $output;
}


/**
 * @param  array<SqlSchema\Column> $objs
 * @return list<string>
 */
function formatColumns(array $objs): array
{
	$res = [];

	foreach ($objs as $obj) {
		$res[] = formatColumn($obj);
	}

	return $res;
}


function formatIndex(SqlSchema\Index $obj): string
{
	$output = $obj->getType();
	$output .= ' ' . $obj->getName() . ' (';
	$first = TRUE;

	foreach ($obj->getColumns() as $column) {
		if (!$first) {
			$output .= ', ';
		}
		$output .= formatIndexColumn($column);
		$first = FALSE;
	}

	$output .= ')';
	return $output;
}


function formatIndexColumn(SqlSchema\IndexColumn $obj): string
{
	$output = $obj->getName();
	$length = $obj->getLength();
	$output .= isset($length) ? '(' . $length . ')' : '';
	$output .= ' ' . $obj->getOrder();
	return $output;
}


/**
 * @param  array<SqlSchema\IndexColumn> $objs
 * @return list<string>
 */
function formatIndexColumns(array $objs): array
{
	$res = [];

	foreach ($objs as $obj) {
		$res[] = formatIndexColumn($obj);
	}

	return $res;
}



function formatForeignKey(SqlSchema\ForeignKey $obj): string
{
	$output = 'CONSTRAINT ' . $obj->getName();
	$output .= ' FOREIGN KEY (';
	$first = TRUE;

	foreach ($obj->getColumns() as $column) {
		if (!$first) {
			$output .= ', ';
		}
		$output .= $column;
		$first = FALSE;
	}

	$output .= ') REFERENCES ' . $obj->getTargetTable() . ' (';
	$first = TRUE;

	foreach ($obj->getTargetColumns() as $targetColumn) {
		if (!$first) {
			$output .= ', ';
		}
		$output .= $targetColumn;
		$first = FALSE;
	}

	$output .= ')';
	return $output;
}


/**
 * @param  array<SqlSchema\ForeignKey> $objs
 * @return list<string>
 */
function formatForeignKeys(array $objs): array
{
	$res = [];

	foreach ($objs as $obj) {
		$res[] = formatForeignKey($obj);
	}

	return $res;
}

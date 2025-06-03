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
 * @param  object|object[] $obj
 * @return mixed
 */
function format($obj)
{
	if (is_array($obj)) {
		$output = [];

		foreach ($obj as $item) {
			$output[] = format($item);
		}

		return $output;
	}

	if ($obj instanceof SqlSchema\Schema) {
		$output = [];

		foreach ($obj->getTables() as $table) {
			$output[$table->getName()] = format($table);
		}

		return $output;

	} elseif ($obj instanceof SqlSchema\Table) {
		$output = [];

		foreach ($obj->getColumns() as $column) {
			$output[] = format($column);
		}

		foreach ($obj->getIndexes() as $index) {
			$output[] = format($index);
		}

		return $output;

	} elseif ($obj instanceof SqlSchema\Column) {
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

	} elseif ($obj instanceof SqlSchema\Index) {
		$output = $obj->getType();
		$output .= ' ' . $obj->getName() . ' (';
		$first = TRUE;

		foreach ($obj->getColumns() as $column) {
			if (!$first) {
				$output .= ', ';
			}
			$output .= format($column);
			$first = FALSE;
		}

		$output .= ')';
		return $output;

	} elseif ($obj instanceof SqlSchema\IndexColumn) {
		$output = $obj->getName();
		$length = $obj->getLength();
		$output .= isset($length) ? '(' . $length . ')' : '';
		$output .= ' ' . $obj->getOrder();
		return $output;

	} elseif ($obj instanceof SqlSchema\ForeignKey) {
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

	throw new Exception("Unknow object " . get_class($obj));
}

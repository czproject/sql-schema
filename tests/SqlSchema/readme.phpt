<?php

use CzProject\SqlSchema\Index;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$schema = new CzProject\SqlSchema\Schema;

	$table = $schema->addTable('book');
	$table->addColumn('id', 'INT', NULL, array('UNSIGNED'));
	$table->addColumn('name', 'VARCHAR', array(200));
	$table->addColumn('author_id', 'INT', NULL, array('UNSIGNED'));
	$table->addIndex(NULL, 'id', Index::TYPE_PRIMARY);
	$table->addIndex('name_author_id', array('name', 'author_id'), Index::TYPE_UNIQUE);

	$schema->getTables();

	Assert::same(array(
		'book' => array(
			'id INT UNSIGNED NOT NULL',
			'name VARCHAR(200) NOT NULL',
			'author_id INT UNSIGNED NOT NULL',
			'PRIMARY  (id ASC)',
			'UNIQUE name_author_id (name ASC, author_id ASC)',
		),
	), format($schema));
});

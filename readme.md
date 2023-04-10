# CzProject\SqlSchema

[![Build Status](https://github.com/czproject/sql-schema/workflows/Build/badge.svg)](https://github.com/czproject/sql-schema/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/czproject/sql-schema.svg)](https://packagist.org/packages/czproject/sql-schema)
[![Latest Stable Version](https://poser.pugx.org/czproject/sql-schema/v/stable)](https://github.com/czproject/sql-schema/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/czproject/sql-schema/blob/master/license.md)

Library for describe of the database schema.


<a href="https://www.janpecha.cz/donate/"><img src="https://buymecoffee.intm.org/img/donate-banner.v1.svg" alt="Donate" height="100"></a>


## Installation

[Download a latest package](https://github.com/czproject/sql-schema/releases) or use [Composer](http://getcomposer.org/):

```
composer require czproject/sql-schema
```

CzProject\SqlSchema requires PHP 5.6.0 or later.


## Usage

``` php
use CzProject\SqlSchema\Index;
$schema = new CzProject\SqlSchema\Schema;

$table = $schema->addTable('book');
$table->addColumn('id', 'INT', NULL, array('UNSIGNED'));
$table->addColumn('name', 'VARCHAR', array(200));
$table->addColumn('author_id', 'INT', NULL, array('UNSIGNED'));
$table->addIndex(NULL, 'id', Index::TYPE_PRIMARY);
$table->addIndex('name_author_id', array('name', 'author_id'), Index::TYPE_UNIQUE);

$schema->getTables();
```

------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/

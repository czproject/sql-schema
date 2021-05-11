
# CzProject\SqlSchema

[![Tests Status](https://github.com/czproject/sql-schema/workflows/Tests/badge.svg)](https://github.com/czproject/sql-schema/actions)

Library for describe of the database schema.


Support Me
----------

Do you like SqlSchema? Are you looking forward to the **new features**?

<a href="https://www.paypal.com/donate?hosted_button_id=BWR5RJCDLY7SG"><img src="https://buymecoffee.intm.org/img/janpecha-paypal-donate@2x.png" alt="PayPal or credit/debit card" width="254" height="248"></a>

<img src="https://buymecoffee.intm.org/img/bitcoin@2x.png" alt="Bitcoin" height="32"> `bc1qrq9egf99a6z3576twggrp6uv5td5r3pq0j4awe`

Thank you!


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

# Comma-Separated Values by RFC 4180
A lightweight database in pure PHP<br>
It will perfectly replace complex databases in simple projects

## Requirements
- PHP 8.4

## Installation
1. `composer require mirzaev/csv`
2. Create a class that inherits `mirzaev/csv/database` and redefine the `database::FILE` constant
3. Enjoy!

## Example
```php
<?php

// Library for CSV
use mirzaev\csv\{database, record};

// Initializing the database
$database = new database('name', 'age', 'created');

// Initializing the record
$record = new record(['Arsen', '23', time());

// Writing to the database
$database->write($record);
?>
```

## Used by
- My site-article about how i was kidnapped by PMC Wagner operatives [mirzaev/repression](https://git.mirzaev.sexy/mirzaev/repression)
- My decentralized P2P blockchain chats project [mirzaev/notchat](https://git.mirzaev.sexy/mirzaev/notchat)

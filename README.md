# Ebaboba database
A lightweight database by pure PHP<br>

At the moment the project is a modified RFC 4180

`2024.12.14` **IN DEVELOPMENT! DO NOT USE IN PROJECTS!**

## Requirements
- PHP 8.4

## Installation
1. `composer require mirzaev/ebaboba`
2. Create a class that inherits `mirzaev/ebaboba/database` and redefine the `database::FILE` constant
3. Enjoy!

## Example
```php
<?php

// Ebaboba database
use mirzaev\ebaboba\{database, record};

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

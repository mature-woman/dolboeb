# Ebaboba
Lightweight binary database by pure PHP<br>

## Dependencies
1. ![PHP 8.4](https://www.php.net/releases/8.4/en.php)
2. ![Composer](https://getcomposer.org/) (php package manager)

## Installation
`composer require mirzaev/ebaboba`

## Example
```php
<?php

use use mirzaev\ebaboba\database,
	mirzaev\ebaboba\column,
	mirzaev\ebaboba\record,
	mirzaev\ebaboba\enumerations\encoding,
	mirzaev\ebaboba\enumerations\type;

// Initializing the database
$database = new database()
	->encoding(encoding::utf8)
	->columns(
		new column('name', type::string, ['length' => 32]),
		new column('second_name', type::string, ['length' => 64]),
		new column('age', type::integer),
		new column('height', type::float)
	)
	->connect(__DIR__ . DIRECTORY_SEPARATOR . 'database.ba');

// Initializing the record
$record = $database->record(
	'Arsen',
	'Mirzaev',
	23,
	(float) 165
);

if ($database->write($record)) {
    // Writed the record into the database

    // Updating the record in the database
    $updated = $database->read(
        filter: fn($record) => $record->name === 'Arsen', 
        update: fn(&$record) => $record->age = 24, 
        amount: 1
    );

    // Reading the record from the database
    $readed = $database->read(
        filter: fn($record) => $record->name === 'Arsen' && $record->age === 24,
        amount: 1
    );

    // Deleting the record from the database
    $deleted = $database->read(
        filter: fn($record) => $record->age < 25,
        delete: true,
        amount: 1000
    );
}
?>
```

## Used by
- My site-article about how i was kidnapped by PMC Wagner operatives [mirzaev/repression](https://git.svoboda.works/mirzaev/repression)
- My decentralized P2P blockchain chats project [mirzaev/notchat](https://git.svoboda.works/mirzaev/notchat)
- Svoboda Telegram chat-robot [svoboda/negotiator](https://git.svoboda.works/svoboda/negotiator)

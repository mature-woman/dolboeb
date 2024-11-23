<?php

use mirzaev\csv\database,
	mirzaev\csv\record;

// Importing files of thr project and dependencies
require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

// Initializing the counter of actions
$action = 0;

// Initializing the test database
$database = new database(
	'empty_value_at_the_beginning',
	'name',
	'second_name',
	'age_between_quotes',
	'true',
	'empty_value',
	'empty_value_between_quotes',
	'number',
	'null',
	'null_between_quotes',
	'float_between_quotes',
	'float',
	'float_long',
	'float_with_two_dots',
	'string_with_doubled_quotes',
	'string_with_doubled_quotes_twice',
	'string_with_space_at_the_beginning',
	'string_with_escaped_comma',
	'string_with_unicode_symbols'
);

echo '[' . ++$action . "] Created the database instance with columns\n";

// Initializing the test record with the test row
$record = new record(...record::deserialize(',"Arsen","Mirzaev","23",true,,"",100,null,"null","102.1",300.34,1001.23145,5000.400.400,"test ""value""","another"" test "" value with ""two double quotes pairs"" yeah"," starts with space","has\, an escaped comma inside","unicode символы"'));

echo '[' . ++$action . "] Created the record with the test row\n";

// Initializing the counter of tests
$test = 0;

echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[0] === null ? 'SUCCESS' : 'FAIL') . "] The empty value at the beginning is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[1] === 'Arsen' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"Arsen\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[2] === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"Mirzaev\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[3] === '23' ? 'SUCCESS' : 'FAIL') . "] The age between quotes value is need to be \"23\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[4] === true ? 'SUCCESS' : 'FAIL') . "] The value is need to be true\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[5] === null ? 'SUCCESS' : 'FAIL') . "] The empty value is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[6] === '' ? 'SUCCESS' : 'FAIL') . "] The empty value between quotes is need to be \"\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[7] === 100 ? 'SUCCESS' : 'FAIL') . "] The value is need to be 100 integer\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[8] === null ? 'SUCCESS' : 'FAIL') . "] The null value is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[9] === 'null' ? 'SUCCESS' : 'FAIL') . "] The null value between quotes is need to be \"null\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[10] === '102.1' ? 'SUCCESS' : 'FAIL') . "] The float value between quotes is need to be \"102.1\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[11] === 300.34 ? 'SUCCESS' : 'FAIL') . "] The float value is need to be 300.34 float \n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[12] === 1001.23145 ? 'SUCCESS' : 'FAIL') . "] The long float value is need to be 1001.23145 float\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[13] === '5000.400.400' ? 'SUCCESS' : 'FAIL') . "] The float value with two dots is need to be \"5000.400.400\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[14] === 'test "value"' ? 'SUCCESS' : 'FAIL') . "] The value with quotes is need to be \"test \"value\"\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[15] === 'another" test " value with "two double quotes pairs" yeah' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"another\" test \" value with \"two double quotes pairs\" yeah\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[16] === ' starts with space' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \" starts with space\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[17] === 'has, an escaped comma inside' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"has, an escaped comma inside\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->parameters[18] === 'unicode символы' ? 'SUCCESS' : 'FAIL') . "] The valueis need to be \"unicode символы\" string\n";

// Combining database columns with  record parameters
$record->columns($database);

echo '[' . ++$action . "] Combined database columns with record parameters\n";


// Reinitializing the counter of tests
$test = 0;

echo '[' . ++$action . '][' . ++$test . '][' . ($record->empty_value_at_the_beginning === null ? 'SUCCESS' : 'FAIL') . "] The empty value at the beginning is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->name === 'Arsen' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"Arsen\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"Mirzaev\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->age_between_quotes === '23' ? 'SUCCESS' : 'FAIL') . "] The age between quotes value is need to be \"23\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->true === true ? 'SUCCESS' : 'FAIL') . "] The value is need to be true\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->empty_value === null ? 'SUCCESS' : 'FAIL') . "] The empty value is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->empty_value_between_quotes === '' ? 'SUCCESS' : 'FAIL') . "] The empty value between quotes is need to be \"\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->number === 100 ? 'SUCCESS' : 'FAIL') . "] The value is need to be 100 integer\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->null === null ? 'SUCCESS' : 'FAIL') . "] The null value is need to be null\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->null_between_quotes === 'null' ? 'SUCCESS' : 'FAIL') . "] The null value between quotes is need to be \"null\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->float_between_quotes === '102.1' ? 'SUCCESS' : 'FAIL') . "] The float value between quotes is need to be \"102.1\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->float === 300.34 ? 'SUCCESS' : 'FAIL') . "] The float value is need to be 300.34 float \n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->float_long === 1001.23145 ? 'SUCCESS' : 'FAIL') . "] The long float value is need to be 1001.23145 float\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->float_with_two_dots === '5000.400.400' ? 'SUCCESS' : 'FAIL') . "] The float value with two dots is need to be \"5000.400.400\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->string_with_doubled_quotes === 'test "value"' ? 'SUCCESS' : 'FAIL') . "] The value with quotes is need to be \"test \"value\"\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->string_with_doubled_quotes_twice === 'another" test " value with "two double quotes pairs" yeah' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"another\" test \" value with \"two double quotes pairs\" yeah\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->string_with_space_at_the_beginning === ' starts with space' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \" starts with space\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->string_with_escaped_comma === 'has, an escaped comma inside' ? 'SUCCESS' : 'FAIL') . "] The value is need to be \"has, an escaped comma inside\" string\n";
echo '[' . ++$action . '][' . ++$test . '][' . ($record->string_with_unicode_symbols === 'unicode символы' ? 'SUCCESS' : 'FAIL') . "] The valueis need to be \"unicode символы\" string\n";

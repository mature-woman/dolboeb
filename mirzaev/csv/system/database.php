<?php

declare(strict_types=1);

namespace mirzaev\csv;

// Files of the project
use mirzaev\csv\traits\file;

// Built-in libraries
use exception;

/**
 * Database
 *
 * Comma-Separated Values by RFC 4180
 *
 * @see https://tools.ietf.org/html/rfc4180 RFC 4180
 * @see https://en.wikipedia.org/wiki/Create,_read,_update_and_delete CRUD
 *
 * @package mirzaev\csv
 *
 * @var string FILE Path to the database file
 * @var array $columns Database columns
 *
 * @method void __construct(array|null $columns) Constructor
 * @method void create() Write to the database file
 * @method array|null read(int $amount, int $offset, bool $backwards, ?callable $filter) Read from the database file
 * @method void update() @todo create + tests
 * @method void delete() @todo create + teste
 * @method void __set(string $name, mixed $value) Write the parameter
 * @method mized __get(string $name) Read the parameter
 * @method void  __unset(string $name) Delete the parameter
 * @method bool __isset(string $name) Check for initializing the parameter
 *
 * @license http://www.wtfpl.net Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */

class database
{
	use file {
		file::read as protected file;
	}

	/**
	 * File
	 *
	 * Path directories to the file will not be created automatically to avoid 
	 * checking the existence of all directories on every read or write operation.
	 *
	 * @var string FILE Path to the database file
	 */
	public const string FILE = 'database.csv';

	/**
	 * Columns
	 *
	 * This property is used instead of adding a check for the presence of the first row 
	 * with the designation of the column names, as well as reading these columns, 
	 * which would significantly slow down the library.
	 *
	 * @see https://www.php.net/manual/en/function.array-combine.php Used when creating a record instance
	 *
	 * @var array $columns Database columns
	 */
	public protected(set) array $columns;

	/**
	 * Constructor
	 *
	 * @param string ...$columns Columns
	 *
	 * @return void
	 */
	public function __construct(string ...$columns)
	{
		// Initializing columns
		if (!empty($columns)) $this->columns = $columns;
	}

	/**
	 * Initialize
	 *
	 * Checking for existance of the database file and creating it
	 *
	 * @return bool Is the database file exists?
	 */
	public static function initialize(): bool
	{
		if (file_exists(static::FILE)) {
			// The database file exists

			// Exit (success)
			return true;
		} else {
			// The database file is not exists

			// Creating the database file and exit (success/fail)
			return touch(static::FILE);
		}
	}

	/**
	 * Create
	 *
	 * Create records in the database file
	 *
	 * @param record $record The record
	 * @param array &$errors Buffer of errors
	 *
	 * @return void
	 */
	public static function write(record $record, array &$errors = []): void
	{
		try {
			// Opening the database file
			$file = fopen(static::FILE, 'c');

			if (flock($file, LOCK_EX)) {
				// The file was locked

				// Writing the serialized record to the database file
				fwrite($file, $record->serialize());

				// Applying changes
				fflush($file);

				// Unlocking the file
				flock($file, LOCK_UN);
			}

			// Deinitializing unnecessary variables
			unset($serialized, $record, $before);

			// Closing the database file
			fclose($file);
		} catch (exception $e) {
			// Write to the buffer of errors
			$errors[] = [
				'text' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'stack' => $e->getTrace()
			];
		}
	}

	/**
	 * Read
	 *
	 * Read records in the database file
	 *
	 * @param int $amount Amount of records
	 * @param int $offset Offset of rows for start reading
	 * @param bool $backwards Read from end to beginning?
	 * @param callable|null $filter Filter for records function($record, $records): bool
	 * @param array &$errors Buffer of errors
	 *
	 * @return array|null Readed records
	 */
	public static function read(int $amount = 1, int $offset = 0, bool $backwards = false, ?callable $filter = null, array &$errors = []): ?array
	{
		try {
			// Opening the database file
			$file = fopen(static::FILE, 'r');

			// Initializing the buffer of readed records
			$records = [];

			// Continuing reading
			offset:

			foreach (static::file(file: $file, offset: $offset, rows: $amount, position: 0, step: $backwards ? -1 : 1) as $row) {
				// Iterating over rows

				if ($row === null) {
					// Reached the end or the beginning of the file

					// Deinitializing unnecessary variables
					unset($row, $record, $offset);

					// Closing the database file
					fclose($file);

					// Exit (success)
					return $records;
				}

				// Initializing record
				$record = new record($row)->combine($this);

				if ($record) {
					// Initialized record

					if ($filter === null || $filter($record, $records)) {
						// Filter passed

						// Writing to the buffer of readed records
						$records[] = $record;
					}
				}
			}

			// Deinitializing unnecessary variables
			unset($row, $record);

			if (count($records) < $amount) {
				// Fewer rows were read than requested

				// Writing offset for reading
				$offset += $amount;

				// Continuing reading (enter to the recursion)
				goto offset;
			}

			// Deinitializing unnecessary variables
			unset($offset);


			// Closing the database file
			fclose($file);

			// Exit (success)
			return $records;
		} catch (exception $e) {
			// Write to the buffer of errors
			$errors[] = [
				'text' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'stack' => $e->getTrace()
			];
		}

		// Exit (fail)
		return null;
	}
}

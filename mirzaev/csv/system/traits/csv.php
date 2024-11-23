<?php

declare(strict_types=1);

namespace mirzaev\csv\traits;

// Files of the project
use mirzaev\csv\interfaces\csv as csv_interface;

// Built-in libraries
use exception;

/**
 * CSV
 *
 * Comma-Separated Values by RFC 4180
 *
 * @see https://tools.ietf.org/html/rfc4180
 *
 * @uses csv_interface
 * @package mirzaev\csv\traits
 *
 * @method static array|null read(int $rows, array &$errors) Read from the start of the database file
 * @method static string|false serialize(array $parameters, bool $created) Preparing data for writing to the database
 * @method static array|false deserialize(string $row) Preparing data from the database to processing
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
trait csv
{
	/**
	 * Read
	 *
	 * Read from the start of the database file
	 *
	 * @param int $rows Amount of rows for reading
	 * @param array &$errors Buffer of errors
	 *
	 * @return array|null Readed records
	 */
	public static function read(int $rows = 0, &$errors = []): ?array
	{
		try {
			// Initializing the buffer of readed records
			$records = [];

			// Opening the file with views records
			$file = fopen(static::FILE, 'c+');

			while (--$rows >= 0 && ($row = fgets($file, 4096)) !== false) {
				// Iterating over rows (records)

				// Deserealizing record
				$deserialized = static::deserialize($row);

				if ($deserialized) {
					// Deserialized record

					// Writing to the buffer of readed records
					$records[] = $deserialized;
				}
			}

			// Closing file with views records
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

	/**
	 * Serialize
	 *
	 * Preparing data for writing to the database
	 *
	 * @param array $parameters Values for serializing
	 * @param bool $created Add date of creating at the end?
	 *
	 * @return string|false Serialized data
	 */
	public static function serialize(array $parameters, bool $created = false): string|false
	{
		// Declaring the buffer of serialized values
		$serialized = '';

		// Sanitizing values
		foreach ($parameters as $value) $serialized .= ',' . preg_replace('/(?<=[^^])"(?=[^$])/', '""', preg_replace('/(?<=[^^]),(?=[^$])/', '\,', $value ?? ''));

		// Writing date of creating to the buffer of serialized values
		if ($created)	$serialized .= ',' . time();

		// Trimming excess first comma in the buffer of serialized values
		$serialized = mb_substr($serialized, 1, mb_strlen($serialized));

		// Exit (success/fail)
		return empty($serialized) ? false : $serialized;
	}

	/**
	 * Deserialize
	 *
	 * Preparing data from the database to processing
	 *
	 * @param string $row Record for deserializing
	 *
	 * @return array|false Deserialized data
	 */
	public static function deserialize(string $row): array|false
	{
		// Separating row by commas
		preg_match_all('/(?:^|,)(?=[^"]|(")?)"?((?(1)[^"]*|[^,"]*))"?(?=,|$)/', $row, $matches);

		// Converting double quotes to single quotes
		foreach ($matches[2] as &$match)
			if (empty($match = preg_replace('/[\n\r]/', '', preg_replace('/""/', '"', preg_replace('/\\\,/', ',', trim((string) $match, '"'))))))
				$match = null;

		// Exit (success/fail)
		return empty($matches[2]) ? false : $matches[2];
	}
}

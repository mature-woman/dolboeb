<?php

declare(strict_types=1);

namespace mirzaev\csv\interfaces;

// Files of the project
use mirzaev\csv\traits\csv as csv_trait;

/**
 * CSV
 *
 * Comma-Separated Values by RFC 4180
 *
 * @see https://tools.ietf.org/html/rfc4180
 *
 * @used-by csv_trait
 * @package mirzaev\csv\interfaces
 *
 * @var string FILE Path to the database file
 *
 * @method void static write() Write to the database file
 * @method array|null static read() Read from the database file
 * @method string|false static serialize() Preparing data for writing to the database
 * @method array|false static deserialize() Preparing data from the database to processing
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
interface csv
{
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
	 * Write
	 *
	 * Write to the database file
	 *
	 * @return void
	 */
	public static function write(): void;

	/**
	 * Read
	 *
	 * Read from the start of the database file
	 *
	 * @param int $rows Amount of rows for reading
	 *
	 * @return array|null Readed records
	 */
	public static function read(int $rows = 1): ?array;

	/**
	 * Last
	 *
	 * Read from the end of the database file
	 *
	 * @param int $rows Amount of rows for reading
	 *
	 * @return array|null Readed records
	 */
	public static function last(int $rows = 1): ?array;

	/**
	 * Serialize
	 *
	 * Preparing data for writing to the database
	 *
	 * @param array $parameters Values for serializing
	 *
	 * @return string|false Serialized data
	 */
	public static function serialize(array $parameters): string|false;

	/**
	 * Deserialize
	 *
	 * Preparing data from the database to processing
	 *
	 * @param string $row Record for deserializing
	 *
	 * @return array|false Serialized data
	 */
	public static function deserialize(string $row): array|false;
}

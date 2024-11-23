<?php

declare(strict_types=1);

namespace mirzaev\csv;

// Files of the project
use mirzaev\csv\database;

/**
 * CSV
 *
 * Comma-Separated Values by RFC 4180
 *
 * @see https://tools.ietf.org/html/rfc4180 RFC 4180
 *
 * @package mirzaev\csv
 *
 * @var array $parameters Parameters of the record
 *
 * @method void __construct(string|null $row) Constructor
 * @method string static serialize() Convert record instance to values for writing into the database
 * @method void static unserialize(string $row) Convert values from the database and write to the record instance
 * @method void __set(string $name, mixed $value) Write the parameter
 * @method mized __get(string $name) Read the parameter
 * @method void  __unset(string $name) Delete the parameter
 * @method bool __isset(string $name) Check for initializing the parameter
 *
 * @license http://www.wtfpl.net Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class record
{
	/**
	 * Parameters
	 *
	 * Mapped with database::COLUMN
	 *
	 * @var array $parameters Parameters of the record
	 */
	public protected(set) array $parameters = [];

	/**
	 * Constructor
	 *
	 * @param string|null $row Row for converting to record instance parameters
	 *
	 * @return void
	 */
	public function __construct(?string $row = null)
	{
		// Initializing parameters
		if (isset($row)) $this->parameters = static::deserialize($row);
	}

	/**
	 * Columns
	 *
	 * Combine parameters of the record with columns of the database
	 * The array of parameters of the record will become associative
	 *
	 * @return static The instance from which the method was called (fluent interface)
	 */
	public function columns(database $database): static
	{
		// Combining database columns with record parameters
		$this->parameters = array_combine($database->columns, $this->parameters);

		// Exit (success)
		return $this;
	}

	/**
	 * Serialize
	 *
	 * Convert record instance to values for writing into the database
	 *
	 * @return string Serialized record
	 */
	public function serialize(): string
	{
		// Declaring the buffer of generated row
		$serialized = '';

		foreach ($this->parameters as $value) {
			// Iterating over parameters

			// Generating row by RFC 4180
			$serialized .= ',' . preg_replace('/(?<=[^^])"(?=[^$])/', '""', preg_replace('/(?<=[^^]),(?=[^$])/', '\,', $value ?? ''));
		}

		// Trimming excess first comma in the buffer of generated row
		$serialized = mb_substr($serialized, 1, mb_strlen($serialized));

		// Exit (success)
		return $serialized;
	}

	/**
	 * Deserialize
	 *
	 * Convert values from the database and write to the record instance
	 *
	 * @param string $row Row from the database
	 *
	 * @return array Deserialized record
	 */
	public function deserialize(string $row): array
	{
		// Separating row by commas
		preg_match_all('/(.*)(?>(?<!\\\),|$)/Uu', $row, $matches);

		// Deleting the last matched element (i could not come up with a better regular expression)
		array_pop($matches[1]);

		// Generating parameters by RFC 4180
		foreach ($matches[1] as &$match) {
			// Iterating over values

			// Declaring buffer of the implementated value
			$buffer = null;

			if ($match === 'null' || empty($match)) {
				// Null

				// Writing to the matches buffer
				$match = null;
			} else if (($buffer = filter_var($match, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE)) !== null) {
				// Boolean

				// Writing to the matches buffer
				$match = $buffer;
			} else if (($buffer = filter_var($match, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) !== null) {
				// Integer

				// Writing to the matches buffer
				$match = $buffer;
			} else if (($buffer = filter_var($match, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)) !== null) {
				// Float

				// Writing to the matches buffer
				$match = $buffer;
			} else {
				// String

				// Deinitializing unnecessary variables
				unset($buffer);

				// Removing quotes from both sides (trim() is not suitable here)
				$unquoted = preg_replace('/"(.*)"/u', '$1', $match);

				// Unescaping commas
				$commaded = preg_replace('/\\\,/', ',', $unquoted);

				// Unescaping quotes (by RFC 4180)
				$quoted = preg_replace('/""/', '"', $commaded);

				// Removing line break characters
				/* $unbreaked =  preg_replace('/[\n\r]/', '', $quoted); */

				// Removing spaces from both sides
				/* $unspaced = trim($unbreaked); */

				// Writing to the matches buffer
				$match = $quoted;
			}

			// Deinitializing unnecessary variables
			unset($buffer);
		}

		// Exit (success)
		return $matches[1];
	}

	/**
	 * Write
	 *
	 * Write the parameter
	 *
	 * @param string $name Name of the parameter
	 * @param mixed $value Content of the parameter
	 *
	 * @return void
	 */
	public function __set(string $name, mixed $value = null): void
	{
		// Writing the parameter and exit (success)
		$this->parameters[$name] = $value;
	}

	/**
	 * Read
	 * 
	 * Read the parameter
	 *
	 * @param string $name Name of the parameter
	 *
	 * @return mixed Content of the parameter
	 */
	public function __get(string $name): mixed
	{
		// Reading the parameter and exit (success)
		return $this->parameters[$name];
	}

	/**
	 * Delete
	 *
	 * Delete the parameter
	 *
	 * @param string $name Name of the parameter
	 *
	 * @return void
	 */
	public function __unset(string $name): void
	{
		// Deleting the parameter and exit (success)
		unset($this->parameter[$name]);
	}

	/**
	 * Check for initializing
	 *
	 * Check for initializing the parameter
	 *
	 * @param string $name Name of the parameter
	 *
	 * @return bool Is the parameter initialized?
	 */
	public function __isset(string $name): bool
	{
		// Checking for initializing the parameter and exit (success)
		return isset($this->parameters[$name]);
	}

}

<?php

declare(strict_types=1);

namespace mirzaev\csv\traits;

// Built-in libraries
use Exception as exception,
	Generator as generator;

/**
 * File
 *
 * @package mirzaev\csv\traits
 *
 * @method static generator|null|false read($file, int $offset, int $rows, int $position, int $step, array &$errors) Read the file
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
trait file
{
	/**
	 * Read
	 *
	 * Read the file
	 *
	 * @param resource $file Pointer to the file (fopen())
	 * @param int $rows Amount of rows for reading
	 * @param int $offset Offset of rows for start reading
	 * @param int $position Initial cursor position on a row
	 * @param int $step Reading step
	 * @param array &$errors Buffer of errors
	 *
	 * @return generator|null|false 
	 */
	private static function read($file, int $rows = 10,  int $offset = 0, int $position = 0, int $step = 1, array &$errors = []): generator|null|false
	{
		try {
			while ($offset-- > 0) {
				do {
					// Iterate over symbols of the row

					// The end (or the beginning) of the file reached (success)
					if (feof($file)) break;

					// Moving the cursor to next position on the row
					fseek($file, $position += $step, SEEK_END);

					// Reading a character of the row
					$character = fgetc($file);

					// Is the character a carriage return? (end or start of the row)
				} while ($character !== PHP_EOL);
			}

			while ($rows-- > 0) {
				// Reading rows

				// Initializing of the buffer of row
				$row = '';

				// Initializing the character buffer to generate $row
				$character = '';

				do {
					// Iterate over symbols of the row

					// The end (or the beginning) of the file reached (success)
					if (feof($file)) break;

					// Building the row
					$row = $step > 0 ? $row . $character : $character . $row;

					// Moving the cursor to next position on the row
					fseek($file, $position += $step, SEEK_END);

					// Reading a character of the row
					$character = fgetc($file);

					// Is the character a carriage return? (end or start of the row)
				} while ($character !== PHP_EOL);

				// Exit (success)
				yield empty($row) ? null : $row;
			}

			// Exit (success)
			return null;
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
		return false;
	}
}

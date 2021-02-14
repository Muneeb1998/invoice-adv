<?php namespace App\Libraries;
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
use App\Libraries\Ci3Zip;
class Ci3DbUtility
{
	private $db;
	public function __construct()
	{
		$this->db = \Config\Database::connect();
	}

	public function backup(array $params = [])
	{
		// If the parameters have not been submitted as an
		// array then we know that it is simply the table
		// name, which is a valid short cut.
		if (is_string($params))
		{
			$params = array('tables' => $params);
		}

		// Set up our default preferences
		$prefs = array(
			'tables'		=> array(),
			'ignore'		=> array(),
			'filename'		=> '',
			'format'		=> 'gzip', // gzip, zip, txt
			'add_drop'		=> TRUE,
			'add_insert'		=> TRUE,
			'newline'		=> "\n",
			'foreign_key_checks'	=> TRUE
		);

		// Did the user submit any preferences? If so set them....
		if (count($params) > 0)
		{
			foreach ($prefs as $key => $val)
			{
				if (isset($params[$key]))
				{
					$prefs[$key] = $params[$key];
				}
			}
		}

		// Are we backing up a complete database or individual tables?
		// If no table names were submitted we'll fetch the entire table list
		if (count($prefs['tables']) === 0)
		{
			$prefs['tables'] = $this->db->listTables();
		}

		// Validate the format
		if ( ! in_array($prefs['format'], array('gzip', 'zip', 'txt'), TRUE))
		{
			$prefs['format'] = 'txt';
		}

		// Is the encoder supported? If not, we'll either issue an
		// error or use plain text depending on the debug settings
		if (($prefs['format'] === 'gzip' && ! function_exists('gzencode'))
			OR ($prefs['format'] === 'zip' && ! function_exists('gzcompress')))
		{
			if ($this->db->db_debug)
			{
				return $this->db->display_error('db_unsupported_compression');
			}

			$prefs['format'] = 'txt';
		}

		// Was a Zip file requested?
		if ($prefs['format'] === 'zip')
		{
			// Set the filename if not provided (only needed with Zip files)
			if ($prefs['filename'] === '')
			{
				$prefs['filename'] = (count($prefs['tables']) === 1 ? $prefs['tables'] : $this->db->getDatabase())
							.date('Y-m-d_H-i', time()).'.sql';
			}
			else
			{
				// If they included the .zip file extension we'll remove it
				if (preg_match('|.+?\.zip$|', $prefs['filename']))
				{
					$prefs['filename'] = str_replace('.zip', '', $prefs['filename']);
				}

				// Tack on the ".sql" file extension if needed
				if ( ! preg_match('|.+?\.sql$|', $prefs['filename']))
				{
					$prefs['filename'] .= '.sql';
				}
			}

			// Load the Zip class and output it
			$oCi3Zip = new Ci3Zip();
			$oCi3Zip->add_data($prefs['filename'], $this->_backup($prefs));
			return $oCi3Zip->get_zip();
		}
		elseif ($prefs['format'] === 'txt') // Was a text file requested?
		{
			return $this->_backup($prefs);
		}
		elseif ($prefs['format'] === 'gzip') // Was a Gzip file requested?
		{
			return gzencode($this->_backup($prefs));
		}

		return;
	}

	protected function _backup(array $params = [])
	{
		if (count($params) === 0)
		{
			return FALSE;
		}

		// Extract the prefs for simplicity
		extract($params);

		// Build the output
		$output = '';

		// Do we need to include a statement to disable foreign key checks?
		if ($foreign_key_checks === FALSE)
		{
			$output .= 'SET foreign_key_checks = 0;'.$newline;
		}

		foreach ( (array) $tables as $table)
		{
			// Is the table in the "ignore" list?
			if (in_array($table, (array) $ignore, TRUE))
			{
				continue;
			}

			// Get the table schema
			$query = $this->db->query('SHOW CREATE TABLE '.$this->db->escapeIdentifiers($this->db->getDatabase().'.'.$table));

			// No result means the table name was invalid
			if ($query === FALSE)
			{
				continue;
			}

			// Write out the table schema
			$output .= '#'.$newline.'# TABLE STRUCTURE FOR: '.$table.$newline.'#'.$newline.$newline;

			if ($add_drop === TRUE)
			{
				$output .= 'DROP TABLE IF EXISTS '.$this->db->protectIdentifiers($table).';'.$newline.$newline;
			}

			$i = 0;
			$result = $query->getResultArray();
			foreach ($result[0] as $val)
			{
				if ($i++ % 2)
				{
					$output .= $val.';'.$newline.$newline;
				}
			}

			// If inserts are not needed we're done...
			if ($add_insert === FALSE)
			{
				continue;
			}

			// Grab all the data from the current table
			$query = $this->db->query('SELECT * FROM '.$this->db->protectIdentifiers($table));
			if (count($query->getResultArray()) === 0)
			{
				continue;
			}

			// Fetch the field names and determine if the field is an
			// integer type. We use this info to decide whether to
			// surround the data with quotes or not

			$i = 0;
			$field_str = '';
			$is_int = array();
			foreach ($query->getFieldData() as $field)
			{
				// Most versions of MySQL store timestamp as a string
				$is_int[$i] = in_array(strtolower($field->type),
							array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'), //, 'timestamp'),
							TRUE);

				// Create a string of field names
				$field_str .= $this->db->escapeIdentifiers($field->name).', ';
				$i++;
			}

			// Trim off the end comma
			$field_str = preg_replace('/, $/' , '', $field_str);

			// Build the insert string
			foreach ($query->getResultArray() as $row)
			{
				$val_str = '';

				$i = 0;
				foreach ($row as $v)
				{
					// Is the value NULL?
					if ($v === NULL)
					{
						$val_str .= 'NULL';
					}
					else
					{
						// Escape the data if it's not an integer
						$val_str .= ($is_int[$i] === FALSE) ? $this->db->escape($v) : $v;
					}

					// Append a comma
					$val_str .= ', ';
					$i++;
				}

				// Remove the comma at the end of the string
				$val_str = preg_replace('/, $/' , '', $val_str);

				// Build the INSERT string
				$output .= 'INSERT INTO '.$this->db->protectIdentifiers($table).' ('.$field_str.') VALUES ('.$val_str.');'.$newline;
			}

			$output .= $newline.$newline;
		}
		// Do we need to include a statement to re-enable foreign key checks?
		if ($foreign_key_checks === FALSE)
		{
			$output .= 'SET foreign_key_checks = 1;'.$newline;
		}

		return $output;
	}
}
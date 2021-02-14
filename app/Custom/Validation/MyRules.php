<?php namespace App\Custom\Validation;
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class MyRules
{
	public function user_validated(string $str, string $fields = NULL, array $data = NULL, string &$error = NULL) : bool{            
		print_r($str);
		die();
	}

	public function required_with_fval($str, string $fields, array $data, &$error = null): bool
	{
	  $_f = explode(':', $fields);
	  $fields = explode(',', $_f[1]);
		$requiredFields = [];
		foreach ($fields as $fld)
		{
			$fld2 = explode('=', $fld);
			$field = $fld2[0];
			if (array_key_exists($field, $data) && $data[$field] == $fld2[1])
			{
				$requiredFields[] = $field;
			}
		}
		// var_dump(count($fields) !== count($requiredFields) || array_key_exists($_f[0], $data));
		// die();
		unset($data[$_f[0]]);
		return (count($fields) !== count($requiredFields) || array_key_exists($_f[0], $data));
			// if(!(count($fields) !== count($requiredFields) || array_key_exists($_f[0], $data))){
			//     // $error = lang('Validation.evenError');
			//     $error = 'This is custom error.';
			//     return false;
			//   }
			//   return true;
	}
}
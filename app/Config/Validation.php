<?php

namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		// \CodeIgniter\Validation\CreditCardRules::class,
		// \App\Custom\Validation\MyRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		// 'list'   => 'CodeIgniter\Validation\Views\list',
		// 'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
	protected $_rules = [
		'id'      		=> 'numeric|max_length[20]',
		'gender'  		=> 'regex_match[/^(m|f)+$/]',
		'name'				=> 'alpha_numeric_space|max_length[40]',
		'email'       => 'valid_email|max_length[50]',
		'username'    => 'min_length[3]',
		'password'    => 'min_length[3]',
		'json' 				=> 'valid_json',
		'ip' 					=> 'valid_ip',
		'url' 				=> 'valid_url',
		'cc' 					=> 'valid_cc_number',
		'phone' 			=> 'regex_match[/^[?0-9-]+$/]|max_length[50]',
		'md5Hash32'		=> 'regex_match[/^[a-f0-9]{32}$/]',
		'md5Hash45'		=> 'regex_match[/^[a-f0-9]{45}$/]',
		'md5Hash50'		=> 'regex_match[/^[a-f0-9]{50}$/]',
		'imgSm' 			=> 'uploaded[{{name}}]|is_image[{{name}}]|max_size[{{name}},1024]',
		'imgMd' 			=> 'uploaded[{{name}}]|is_image[{{name}}]|max_size[{{name}},2048]',
		'imgLg' 			=> 'uploaded[{{name}}]|is_image[{{name}}]|max_size[{{name}},3072]',
		'location' 		=> 'regex_match[/^[?a-zA-Z0-9 ,.-]	]|max_length[100]',
		'latlng'  		=> 'regex_match[/^[?0-9.-]+$/]|max_length[20]',
		'text' 				=> 'regex_match[/^[?a-zA-Z0-9_ -- ( ) ,.]+$/]|max_length[1000]',
		'number' 				=> 'regex_match[/^[?0-9]+$/]',
		'decimal' 				=> 'regex_match[/^[?0-9.]+$/]|decimal',
		'discount_rate'    => 'regex_match[/^[?0-9%.]+$/]',
		'bool'  			=> 'regex_match[/^(0|1)$/]',
		'account_type' => 'regex_match[/^(individual|organisation)+$/]',
		'country'		=> 'regex_match[/^[A-Z]{2}$/]',
		'currency'		=> 'regex_match[/^[A-Z]{3}$/]',
		'id'  				=> 'regex_match[/^[0-9]+$/]',
		'uc_password' 	=> 'matches[npass]',
		'dd-mm-yyyy'			=> 'regex_match[/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/]',
	];
	protected $_validation = [
		'u_admin' => [
			'required>username:email',
			'required>password',
		],
		'c_user' => [
			'required>branch:name',
			// 'required>country_id:id',
			// 'required>password',
			// 'required|matches[password]>pass_confirm:password',
		],

		'c_admin' => [
			'required>name',
			'required>email',
			'required>password',
		],
		'client' => [
			'required>name:name',
			// 'permit_empty>company:name',
			'permit_empty>addr1:text',
			'permit_empty>addr2:text',
			'permit_empty>city:name',
			'permit_empty>state:name',
			'permit_empty>zip:number',
			'permit_empty>country',
			'required>email',
			'permit_empty>web_addr:url',
			'permit_empty>phone_contact:text',
			'permit_empty>mobile_contact:text',
			'permit_empty>fax:text',
		],
		'c_invoice' => [
			'required>client_name:number',
		],
		'u_invoice' => [
			'permit_empty>client_name:text',
		],
		'invoice' => [
			'required>client:name',
			'required>invoice_no:text',
			'required>currency',
			'required>issue_date:dd-mm-yyyy',
			'required>invoice_due:number',
			'required>invoice_due_in:dd-mm-yyyy',
			'required>item_desciption.*:text',
			'required>quantity.*:number',
			'required>amount.*:decimal',
			'permit_empty>discount_name:text',
			'permit_empty>discount_rate',
			// 'permit_empty>footer:text',
			'required>to:email',
			'required>from:email',
			'required>sub_total:decimal',
			'permit_empty>discount_amount:text',
			'required>total:decimal',
		],
		'settings' => [
			// 'required>company:name',
			'required>paypal_email:email',
            'required>logo_size:phone',
			'required>footer_email:email',
			'required>mobile:text',
			'required>invoice_pattern:text',
			'required>street1:text',
			'permit_empty>street2:text',
			'required>city:name',
			'required>state:name',
			'required>zip_code:number',
			'required>country',

		],
		'c_settings' => [
			'required>invoice_logo:imgSm',
		],
		'u_admin' => [
			'required>pass:password',
			'required>npass:password',
			'required>rpass:uc_password',
		],
		// 'c_smtp_settings ' => [
		// 	'required>smtpHost',
		// 	'required>smtpUsername:name',
		// 	'required>smtpPass:smtpPassword',
		// 	'required>smtpPort',
		// 	'permit_empty>bcc:email',
		// 	'permit_empty>replyTo:email',
		// 	'permit_empty>setFromEmail:email',
		// 	'required>setFromName:name',
		// 	'required>SMTPSecure',
		// ],
	];

	public function __construct()
	{
		foreach ($this->_validation as $k => $v) {
			if (empty($v)) {
				$this->$k = [];
			} else {
				foreach ($v as $k2 => $v2) {
					$aExp = explode('>', $v2);
					// if (count($aExp) > 1) {
					$rule = $aExp[0];
					$field = $aExp[1];
					$rules = $rule;
					if ($hasRule = strstr($field, ':', false)) {
						$field = str_replace($hasRule, '', $field);
						$rules = $rule . (!empty($rule) ? '|' : '') . $this->_rules[substr($hasRule, 1)];
					} else {
						$rules = $rule . (!empty($rule) ? '|' : '') . $this->_rules[$field];
					}
					$rules = str_replace('{{name}}', $field, $rules);
					// echo $field.' => '.$rules;
					// die();
					$this->$k[$field] = [
						'label'  => lang('RulesLabel.' . $field),
						// 'label' => $field,
						'rules' => $rules
					];
					// }else{
					// 	$this->$k[$aExp[0]] = $rules;
					// }
				}
			}
		}
		unset($this->_validation);
		unset($this->_rules);
		// print_r($aExp);
		// die();
		$this->murgeRules();
		// echo '<pre>';
		// print_r($this);
		// echo '</pre>';
		// die();
		// parent::__construct();
	}

	private function murgeRules()
	{
		$this->c_client = array_merge($this->client);
		$this->u_client = array_merge($this->client);
		$this->c_settings = array_merge($this->settings, $this->c_settings);
		$this->u_settings = array_merge($this->settings);
		$this->c_invoice = array_merge($this->invoice, $this->c_invoice);
		$this->u_invoice = array_merge($this->invoice, $this->u_invoice);
		unset($this->invoice);
		unset($this->client);
		unset($this->settings);
	}
}

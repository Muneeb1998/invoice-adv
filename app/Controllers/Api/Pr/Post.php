<?php

namespace App\Controllers\Api\Pr;

use CodeIgniter\API\ResponseTrait;

class Post extends BaseController
{
	use ResponseTrait;
	public function _j_addAdmin()
	{
		$aPost = $this->aVars;
		$aRes = array();
		$oAdminBuilder = $this->modelHelper->getBuilder('admin', ['act' => 'c']);
		if (!$oAdminBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oAdminBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$oAdminBuilder->cleanRules();
		$oAdminBuilder->selectCount('id');
		$oAdminBuilder->where('email', $this->aVars['email']);
		$count = $oAdminBuilder->countAllResults();
		if ($count >= 1) {
			throw new \RuntimeException('Email already exisit');
		}
		$aData = array(
			'name' => $aPost['name'],
			'email' => $aPost['email'],
			'password' => password_hash($aPost['password'], PASSWORD_DEFAULT),
			'identity' => $aPost['password'],
			'role' => 'a',
		);
		$oAdminBuilder->skipValidation();
		if (!$oAdminBuilder->insert($aData)) {
			throw new \RuntimeException('Error: ' . $oAdminBuilder->errors());
		}
		return [];
	}
	public function _j_Addclient()
	{
		$aPost = $this->aVars;
		$aRes = array();
		$oClientBuilder = $this->modelHelper->getBuilder('client', ['act' => 'c']);
		if (!$oClientBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oClientBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$oClientBuilder->cleanRules();
		// $oClientBuilder->select('id');
		// $oClientBuilder->where('email', $this->aVars['email']);
		// $count = $oClientBuilder->findAll();
		// if ($count) {
		// 	throw new \RuntimeException('Email already exisit');
		// }
		$aData = array(
			'name'      	=> $aPost['name'],
			'company' 		=> $aPost['company'],
			'addr1'  		=> $aPost['addr1'],
			'addr2'  		=> $aPost['addr2'],
			'city'			=> $aPost['city'],
			'state'			=> $aPost['state'],
			'zip' 			=> $aPost['zip'],
			'country' 		=> $aPost['country'],
			'email' 		=> $aPost['email'],
			'web_addr' 		=> $aPost['web_addr'],
			'phone_contact'	=> $aPost['phone_contact'],
			'mobile_contact' => $aPost['mobile_contact'],
			'fax' 			=> $aPost['fax'],
			'created_by' 	=> $this->session->get('userId')
		);
		$oClientBuilder->skipValidation();
		if (!$oClientBuilder->insert($aData)) {
			throw new \RuntimeException('Error: ' . $oClientBuilder->errors());
		}
		return [];
	}
	public function _j_addInvoice()
	{
		$aPost = $this->aVars;
		$aAmount = $aPost['amount'];
		$aItemDesciption = $aPost['item_desciption'];
		$aQuantity = $aPost['quantity'];
		foreach($aAmount as $k => $v){
		    if($v==''){
		        throw new \Exception('Error: Amount(s) is required.');
		    }elseif($aItemDesciption[$k] == ''){
		         throw new \Exception('Error: Item & Desciption is required.');
		    }elseif($aQuantity[$k] == ''){
		         throw new \Exception('Error: Quantity is required.');
		    }
		}
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'c']);
		if (!$oInvoiceBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oInvoiceBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$hash = generateAppHash2([
			'id'		=> $aPost['client_name'],
			'email'	=> $aPost['to'],
			'model' => $oInvoiceBuilder,
			'col'		=> 'access_hash',
		]);
		if ($aPost['invoice_due'] == 6) {
			$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aPost['invoice_due_in']));
			// $dueDate = $aPost['invoice_due_in'];
		} else {
			$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aPost['issue_date'])) . ' + ' . $aPost['invoice_due'] . 'day'));
		}
		$aData = array(
			'client_id' => $aPost['client_name'],
			'invoice_no' => $aPost['invoice_no'],
			'currency' => $aPost['currency'],
			'issue_date' => $aPost['issue_date'],
			'invoice_due' => $aPost['invoice_due'],
			'invoice_due_in' => $aPost['invoice_due_in'],
			'item_desciption' => json_encode($aPost['item_desciption']),
			'quantity' => json_encode($aPost['quantity']),
			'amount' => json_encode($aPost['amount']),
			'discount_rate' => $aPost['discount_rate'],
			'sub_total' => $aPost['sub_total'],
			'discount_name' => $aPost['discount_name'],
			'discount_amount' => $aPost['discount_amount'],
			'total' => $aPost['total'],
			'footer' => $aPost['footer'],
			'access_hash' => $hash,
			'created_by' 	=> $this->session->get('userId')
		);
		$oInvoiceBuilder->skipValidation();
		if (!$oInvoiceBuilder->insert($aData)) {
			throw new \RuntimeException('Error: ' . $oInvoiceBuilder->errors());
		}
		// if (isset($aPost['status'])) {
		// 	if ($aPost['status'] == 1) {
		// 		helper('email');
		// 		sendEmail([
		// 			'e'	 => 'invoiceSend',
		// 			'to' =>  $aPost['to'],
		// 			'invoice_no' => $aPost['invoice_no'],
		// 			'msg' => $aPost['msg'],
		// 			'client_name' => $aPost['client'],
		// 			'amount' => $aPost['total'] . ' '  . $aPost['currency'],
		// 			'due_date' => dateConvert(array(
		// 				'rDate' => 'M dd yyyy',
		// 				'date' => $dueDate
		// 			)),
		// 			'hash' => $hash
		// 		]);
		// 	}
		// }
		return ['data' => $oInvoiceBuilder->insertID()];
	}
	public function _j_settings()
	{
		$oInvoiceBuilder = $this->modelHelper->getBuilder('settings', ['act' => 'c']);
		if (!$oInvoiceBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oInvoiceBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$aPost = $this->aVars;
		$aData = array(
			'company' => $aPost['company'],
			'paypal_email' => $aPost['paypal_email'],
			'mobile' => $aPost['mobile'],
			'invoice_pattern' => $aPost['invoice_pattern'],
			'street1' => $aPost['street1'],
			'street2' => $aPost['street2'],
			'city' => $aPost['city'],
			'state' => $aPost['state'],
			'zip_code' => $aPost['zip_code'],
			'logo_size' => $aPost['logo_size'],
			'country' => $aPost['country'],
			'footer_email' => $aPost['footer_email'],
		);
		$file = $this->request->getFile('invoice_logo');
		if ($file->getPath()) {
			if (!$file->isValid()) {
				throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
			}
            // $file = FCPATH.'assets/uploads/invoice-logo'.$this->session->get('userId').'png';
			// if(file_exists($file)){
			// 	unlink($file);
			// }
			$fileUploaded = uploadFile($file, "invoice-logo", $this->session->get('userId'));
			if ($fileUploaded) {
				$aData['invoice_logo'] = $fileUploaded;
			}
		} else {
			throw new \RuntimeException('Error: Invoice logo is required');
		}
		$aData['admin_id'] = $this->session->get('userId');
		$oInvoiceBuilder->skipValidation();
		if (!$oInvoiceBuilder->insert($aData)) {
			throw new \RuntimeException('Error: ' . $oInvoiceBuilder->errors());
		}
		return [];
	}
	function _j_changePassword()
	{
		$aData = $this->aVars;
		$aRes = array();
		// $oBranch->cleanRules();
		$validation =  \Config\Services::validation();
		if (!$validation->run($this->aVars, 'u_admin')) {
			$this->aProtectedData['oValidationErrors'] = $validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$oAdminBuilder = $this->modelHelper->getBuilder('admin', ['act' => 'u']);
		$oAdminBuilder->select('*');
		$oAdminBuilder->where(['id'	=> $this->session->get('userId')]);
		$record = $oAdminBuilder->get()->getRowArray();
		if (!password_verify($this->aVars['pass'], $record['password'])) {
			throw new \Exception('Error: Inncorrect Current Password.');
		}
		$new_password = password_hash($this->aVars['npass'], PASSWORD_DEFAULT);
		$oAdminBuilder->cleanRules();
		$oAdminBuilder->skipValidation();

		$update_arr = array(
			'password'	=> $new_password,
			'identity' => $this->aVars['npass']
		);
		$oAdminBuilder->set($update_arr);
		$oAdminBuilder->where(['id'	=> $this->session->get('userId')]);
		$oAdminBuilder->update();

		return ['data' => $aRes];
	}
	function _j_mailSettings()
	{
		$aRes = array();
		$oSettingBuilder = $this->modelHelper->getBuilder('SmtpSettings', ['act' => 'c']);
		// if (!$oSettingBuilder->validate($this->aVars)) {
		// 	$this->aProtectedData['oValidationErrors'] = $oSettingBuilder->validation->getErrors();
		// 	throw new \Exception('Error: Invalid form field(s).');
		// }
		$aData = array(
			'smtpHost' => 'mail.invoiceyours.com',
			'smtpUsername' => 'invoice@invoiceyours.com',
			'smtpPass' => 'IBlc059ILw8OV',
			'smtpPort' => '465',
			'bcc' => $this->aVars['bcc'],
			'replyTo' => $this->aVars['replyTo'],
			'setFromEmail' => $this->aVars['setFromEmail'],
			'setFromName' => $this->aVars['setFromName'],
			'SMTPSecure' => 'ssl',
			'auth' =>  1,
		);
		// $oSettingBuilder->truncate();
		$oSettingBuilder->cleanRules();
		// foreach ($aData as $k => $v) {
		$oSettingBuilder->select('id');
		// $oSettingBuilder->where([
		// 	'type' => 'mail',
		// 	'key'	 => $k,
		// 	'admin_id' => $this->session->get('userId')
		// ]);
		$oHasRow = $oSettingBuilder->where('admin_id', $this->session->get('userId'))->get()->getRow();
		$oSettingBuilder->skipValidation();
		$bCrOoUp = true;
		if ($oHasRow) {
			$oSettingBuilder->where('id', $oHasRow->id);
			$oSettingBuilder->set($aData);
			$bCrOoUp = $oSettingBuilder->update();
		} else {
			$aData['admin_id'] = $this->session->get('userId');
			$bCrOoUp = $oSettingBuilder->insert($aData);
		}
		// print_r($aParam);
		if (!$bCrOoUp) {
			throw new \Exception($oSettingBuilder->errors());
		}
		// }
		return ['data' => $aRes];
	}
}

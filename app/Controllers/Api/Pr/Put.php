<?php

namespace App\Controllers\Api\Pr;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\PrApiMethods;

class Put extends BaseController
{
	use ResponseTrait;
	function _j_updateclient()
	{
		$aPut = $this->request->getRawInput();
		$aRes = array();
		$oClientBuilder = $this->modelHelper->getBuilder('client', ['act' => 'u']);
		if (!$oClientBuilder->validate($aPut)) {
			$this->aProtectedData['oValidationErrors'] = $oClientBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$oClientBuilder->cleanRules();
		$oClientBuilder->selectCount('id');
		$oClientBuilder->where('email', $aPut['email'])->whereNotIn(
			'id',
			array($aPut['id'])
		);
		$count = $oClientBuilder->countAllResults();
		// if ($count >= 1) {
		// 	throw new \RuntimeException('Email already exisit');
		// }
		$aData = array(
			'name'      	=> $aPut['name'],
			'company' 		=> $aPut['company'],
			'addr1'  		=> $aPut['addr1'],
			'addr2'  		=> $aPut['addr2'],
			'city'			=> $aPut['city'],
			'state'			=> $aPut['state'],
			'zip' 			=> $aPut['zip'],
			'country' 		=> $aPut['country'],
			'email' 		=> $aPut['email'],
			'web_addr' 		=> $aPut['web_addr'],
			'phone_contact'	=> $aPut['phone_contact'],
			'mobile_contact' => $aPut['mobile_contact'],
			'fax' 			=> $aPut['fax'],
			'created_at' 	=> $this->session->get('userId')
		);
		// die();
		$oClientBuilder->skipValidation();
		$oClientBuilder->set($aData);
		$oClientBuilder->where('id', $aPut['id']);
		$oClientBuilder->update();
		return [];
	}
	public function _j_settings()
	{
		$oInvoiceBuilder = $this->modelHelper->getBuilder('settings', ['act' => 'u']);
		if (!$oInvoiceBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oInvoiceBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$aPut = $this->aVars;
		$aData = array(
			'company' => $aPut['company'],
			'paypal_email' => $aPut['paypal_email'],
			'mobile' => $aPut['mobile'],
			'invoice_pattern' => $aPut['invoice_pattern'],
			'street1' => $aPut['street1'],
			'street2' => $aPut['street2'],
			'city' => $aPut['city'],
			'state' => $aPut['state'],
			'zip_code' => $aPut['zip_code'],
			'country' => $aPut['country'],
			'logo_size' => $aPut['logo_size'],
			'footer_email' => $aPut['footer_email'],
		);
		$file = $this->request->getFile('invoice_logo');
		if ($file->getPath()) {
			if (!$file->isValid()) {
				throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
			}
			$unlinkFile = DIR_ASSETS . 'uploads/invoice-logo/' . $this->session->get('userId') . '.png';
			// echo $unlinkFile;
			// var_dump(file_exists($unlinkFile));
			// die();
			if (file_exists($unlinkFile)) {
				unlink($unlinkFile);
			}
			$fileUploaded = uploadFile($file, "invoice-logo", $this->session->get('userId'));
			if ($fileUploaded) {
				$aData['invoice_logo'] = $fileUploaded;
			}
		}
		$oInvoiceBuilder->skipValidation();
		$oInvoiceBuilder->where('admin_id', $this->session->get('userId'));
		$oInvoiceBuilder->set($aData);
		if (!$oInvoiceBuilder->update()) {
			throw new \RuntimeException('Error: ' . $oInvoiceBuilder->errors());
		}
		return [];
	}
	function _j_paidInvoice()
	{
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		$oInvoiceBuilder->skipValidation();
		$aPut = $this->request->getRawInput();
		$aData =  $oInvoiceBuilder->select([
			'invoice.status',
		])
			->where('invoice.id', $aPut['id'][0])->get()->getRowArray();
		$status = $aData['status'];
		if ($status == "0" || $status == "3") {
			throw new \RuntimeException('Error: You can\'t change invoice to paid');
		}
		$aData =  $oInvoiceBuilder->select([
			'invoice.status',
			'invoice.id',
			'CONCAT(invoice.total," ",invoice.currency) as total',
			'invoice.total as payment_amount',
			'invoice.currency as currency',
			'invoice.invoice_no',
			'invoice.access_hash as hash',
			'admin.email as admin_email',
			'admin.id as user_id',
			'admin.name as admin_name',
			'client.email as client_email',
			'client.name as client_name',
			'settings.company as company',
			'client.id as client',
		])
			->whereIn('invoice.id', $aPut['id'])
			->join('client', 'client.id=invoice.client_id')
			->join('admin', 'admin.id=invoice.created_by')
			->join('settings', 'admin.id=settings.admin_id')->get()->getResultArray();
		$oPaymentBuilder = $this->modelHelper->getBuilder('payment', ['act' => 'c']);
		foreach ($aData as $k => $v) {
			$v['pay_date'] =  dateConvert(array('rDate' => 'M dd yyyy', 'date' => date('Y-m-d')));
			$oPaymentBuilder->skipValidation();
			$aData = [
				'invoice_id' => $aPut['id'][$k],
				'client_id' => $v['client'],
				'payment_currency' => $v['currency'],
				'payment_amount' => $v['payment_amount'],
			];
			$oPaymentBuilder->insert($aData);
			helper('email');
			sendEmail([
				'e'	 => 'invoicePaidClient',
				'to' => $v['client_email'],
				'invoice_no' => $v['invoice_no'],
				'client_name' => $v['client_name'],
				'total' => $v['total'],
				'pay_date' => $v['pay_date'],
				'access_hash' => $v['hash'],
				'user_id' => $v['user_id'],
				'company' => $v['company'],
			]);
			sendEmail([
				'e'	 => 'invoicePaidAdmin',
				'to' => $v['admin_email'],
				'invoice_no' => $v['invoice_no'],
				'admin_name' => $v['admin_name'],
				'total' => $v['total'],
				'user_id' => $v['user_id'],
				'company' => $v['company'],
			]);
		}
		$aRes = array();
		$oInvoiceBuilder->whereIn('id', $aPut['id']);
		$oInvoiceBuilder->set(array(
			'status' => "3"
		));
		$oInvoiceBuilder->update();
		return [];
	}
	function _j_unpaidInvoice()
	{
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		$oInvoiceBuilder->skipValidation();
		$aPut = $this->request->getRawInput();
		$status =  $oInvoiceBuilder->select('status')
			->where('id', $aPut['id'][0])->get()->getRowArray();
		$status = $status['status'];
		if ($status != "3") {
			throw new \RuntimeException('Error: You can\'t change invoice to unpaid');
		}
		$aRes = array();
		$oInvoiceBuilder->whereIn('id', $aPut['id']);
		$oInvoiceBuilder->set(array(
			'status' => "2"
		));
		$oInvoiceBuilder->update();
		return [];
	}
	public function _j_sendInvoice()
	{
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		$oInvoiceBuilder->skipValidation();
		$aPut = $this->request->getRawInput();

		$aData =  $oInvoiceBuilder->select([
			'invoice.invoice_no',
			'client.name as client',
			'client.email as to',
			'CONCAT(invoice.total," ",invoice.currency) as total',
			'invoice.invoice_due_in',
			'invoice.invoice_due',
			'invoice.issue_date',
			'invoice.access_hash'
		])
			->where('invoice.id', $aPut['id'])
			->join('client', 'client.id=invoice.client_id')
			->get()->getRowArray();
		if (!$aData) {
			throw new \RuntimeException('Error: Invoice not sent');
		}
		$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aData['issue_date']));
		// if ($aData['invoice_due'] == 6) {
		// 	$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aData['invoice_due_in']));
		// 	// $dueDate = $aPut['invoice_due_in'];
		// } else {
		// 	$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aData['issue_date'])) . ' + ' . $aData['invoice_due'] . 'day'));
		// }
		$aMail = [
			'e'	 => 'invoiceSend',
			'to' =>  $aData['to'],
			'invoice_no' => $aData['invoice_no'],
			'msg' => $aPut['msg'],
			'bcc' => $aPut['bcc'],
			'client_name' => $aData['client'],
			'amount' => $aData['total'],
			'due_date' => dateConvert(array(
				'rDate' => 'M dd yyyy',
				'date' => $dueDate
			)),
			'hash' => $aData['access_hash']
		];
		$oInvoiceBuilder->where('id', $aPut['id']);
		$oInvoiceBuilder->set(array(
			'status' => "1"
		));
		$oInvoiceBuilder->update();
		helper('email');
		sendEmail($aMail);
		return [];
	}
	function _j_updateInvoice()
	{
		$aPut = $this->request->getRawInput();
		$aPut = $this->aVars;
		$aAmount = $aPut['amount'];
		$aItemDesciption = $aPut['item_desciption'];
		$aQuantity = $aPut['quantity'];
		foreach ($aAmount as $k => $v) {
			if ($v == '') {
				throw new \Exception('Error: Amount(s) is required.');
			} elseif ($aItemDesciption[$k] == '') {
				throw new \Exception('Error: Item & Desciption is required.');
			} elseif ($aQuantity[$k] == '') {
				throw new \Exception('Error: Quantity is required.');
			}
		}
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		if (!$oInvoiceBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oInvoiceBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$aData = array(
			'currency' => $aPut['currency'],
			'issue_date' => $aPut['issue_date'],
			'invoice_due' => $aPut['invoice_due'],
			'invoice_due_in' => $aPut['invoice_due_in'],
			'item_desciption' => json_encode($aPut['item_desciption']),
			'quantity' => json_encode($aPut['quantity']),
			'amount' => json_encode($aPut['amount']),
			'discount_rate' => $aPut['discount_rate'],
			'sub_total' => $aPut['sub_total'],
			'discount_name' => $aPut['discount_name'],
			'discount_amount' => $aPut['discount_amount'],
			'total' => $aPut['total'],
			'footer' => $aPut['footer'],
		);
		if ($aPut['client_name'] != '') {
			$aData['client_id'] = $aPut['client_name'];
		}
		$oInvoiceBuilder->skipValidation();
		$oInvoiceBuilder->where('id', $aPut['id']);
		$oInvoiceBuilder->set($aData);
		if (!$oInvoiceBuilder->update()) {
			throw new \RuntimeException('Error: ' . $oInvoiceBuilder->errors());
		}
		return [];
	}
	function _j_paidMail()
	{
		$oPaymentBuilder = $this->modelHelper->getBuilder('payment', ['act' => 'u']);
		$oPaymentBuilder->skipValidation();
		$aPut = $this->request->getRawInput();

		$aData =  $oPaymentBuilder->select([
			'payment.id',
			'payment.create_at as pay_date',
			'CONCAT(invoice.total," ",invoice.currency) as total',
			'invoice.invoice_no',
			'invoice.access_hash as hash',
			'admin.email as admin_email',
			'admin.id as user_id',
			'admin.name as admin_name',
			'client.email as client_email',
			'client.name as client_name',
			'settings.company as company',
		])
			->where('payment.is_mailed', 0)
			->join('invoice', 'invoice.id=payment.invoice_id')
			->join('admin', 'admin.id=invoice.created_by')
			->join('settings', 'admin.id=settings.admin_id')
			->join('client', 'client.id=invoice.client_id')
			->get()->getResultArray();
		helper('email');
		foreach ($aData as $k => $v) {
			$v['pay_date'] = explode(' ', $v['pay_date']);
			$v['pay_date'] = $v['pay_date'][0];
			$v['pay_date'] = dateConvert(array('rDate' => 'M dd yyyy', 'date' => $v['pay_date']));
			sendEmail([
				'e'	 => 'invoicePaidClient',
				'to' => $v['client_email'],
				'invoice_no' => $v['invoice_no'],
				'client_name' => $v['client_name'],
				'total' => $v['total'],
				'pay_date' => $v['pay_date'],
				'access_hash' => $v['hash'],
				'user_id' => $v['user_id'],
				'company' => $v['company'],
			]);
			sendEmail([
				'e'	 => 'invoicePaidAdmin',
				'to' => $v['admin_email'],
				'invoice_no' => $v['invoice_no'],
				'admin_name' => $v['admin_name'],
				'total' => $v['total'],
				'user_id' => $v['user_id'],
				'company' => $v['company'],
			]);
			$oPaymentBuilder->skipValidation();
			$oPaymentBuilder->where('id', $v['id']);
			$oPaymentBuilder->set(array('is_mailed' => 1));
			if (!$oPaymentBuilder->update()) {
				throw new \RuntimeException('Error: ' . $oPaymentBuilder->errors());
			}
		}
		return [];
	}
	function _j_admin()
	{
		$aRes = array();
		$aPut = $this->request->getRawInput();
		$aData = array(
			"name" => $aPut["name"]
		);
		if ($aPut["password"]) {
			$aData['password'] = password_hash($aPut['password'], PASSWORD_DEFAULT);
			$aData['identity'] = $aPut['password'];
		}
		$oAdminBuilder = $this->modelHelper->getBuilder('admin');
		$oAdminBuilder->skipValidation();
		$oAdminBuilder->set($aData);
		$oAdminBuilder->where('id', $aPut["id"]);
		$oAdminBuilder->update();
		return ['data' => $aRes];
	}
	function _j_archieve()
	{
		$oClientBuilder = $this->modelHelper->getBuilder('client', ['act' => 'u']);
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		$oClientBuilder->skipValidation();
		$aPut = $this->request->getRawInput();
		$aData =  $oClientBuilder->select([
			'client.id',
		])
			->join('invoice', 'client.id=invoice.client_id')
			->whereIn('invoice.id', $aPut['id'])
			->get()->getResultArray();
		$aClientId  = [];
		foreach ($aData as $k => $v) {
			if (!in_array($v['id'], $aClientId)) {
				$oClientBuilder->where('id', $v['id']);
				$oClientBuilder->set(['is_archive' => 1]);
				if (!$oClientBuilder->update()) {
					throw new \RuntimeException('Error: ' . $oClientBuilder->errors());
				}
			}
		}
			$oInvoiceBuilder->whereIn('id', $aPut['id']);
			$oInvoiceBuilder->set(['status' => 11]);
			if (!$oInvoiceBuilder->update()) {
				throw new \RuntimeException('Error: ' . $oInvoiceBuilder->errors());
			}
		return [];
	}
}

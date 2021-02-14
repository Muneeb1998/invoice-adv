<?php

namespace App\Controllers\Api\Pu;

use CodeIgniter\API\ResponseTrait;

class Post extends BaseController
{
	use ResponseTrait;
	public function _j_login()
	{
		$aRes = array();
		$validation =  \Config\Services::validation();
		$aPost = $this->request->getPost();
		$oAdminBuilder = $this->modelHelper->getBuilder('admin', ['act' => 'u']);
		if (!$oAdminBuilder->validate($this->aVars)) {
			$this->aProtectedData['oValidationErrors'] = $oAdminBuilder->validation->getErrors();
			throw new \Exception('Error: Invalid form field(s).');
		}
		$record = 0;
		$loginCred = array(
			'email' => $aPost['username'],

		);
		$oAdminBuilder->select([
			'admin.*',
		]);
		$oAdminBuilder->where($loginCred);
		$record = $oAdminBuilder->get()->getRowArray();
		if ($record == 0) {
			throw new \Exception('Error: Email Not Found.');
		}
		if (!password_verify($aPost['password'], $record['password'])) {
			throw new \Exception('Error: Inncorrect Password.');
		}
		$loginCred = array(
			'isLoggedIn' 	=>  TRUE,
			'userId'		=>	$record['id'],
			'userName'		=>	$record['name'],
			'role' 			=>  $record['role'],
			'email'			=> 	$record['email']
		);
		$this->session->set($loginCred);
		$oAdminBuilder->set(array(
			'last_login_at' => date("Y-m-d H:i:s")
		));
		$oAdminBuilder->where('id', $record['id']);
		$oAdminBuilder->update();
		$aRes = "Login Successfull!";
		return ['data' => $aRes];
	}
	function _j_payInvoice()
	{
		$id = $this->aVars['id'];
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'r']);
		$oInvoiceBuilder->select([
			'invoice.total',
			'invoice.currency',
			'invoice.client_id',
			'invoice.access_hash',
			'settings.paypal_email'
		]);
		$oInvoiceBuilder->where('invoice.id', $id)
		->join('settings','settings.admin_id=invoice.created_by','both');
		$oInvoice = $oInvoiceBuilder->get()->getRow();
		$returnURL = site_url('clientinvoice/'.$oInvoice->access_hash); 
		$notifyURL = site_url('payment/notify.php');
		// print_r($oInvoice);
		// die();
		$aData = array(
			'business' => SANDBOX ? bUSINESS : $oInvoice->paypal_email,
			'item_name' => $oInvoice->client_id,
			'item_number' => $id,
			'amount' => $oInvoice->total,
			'no_shipping' => '1',
			'currency_code' => $oInvoice->currency,
			'notify_url' => $notifyURL,
			'cancel_return' => $returnURL,
			'return' => $returnURL,
			'cmd' => '_xclick',
		);
		return ['data' =>  $aData];
	}
}

<?php

namespace App\Controllers\Api\Pu;

class Get extends BaseController
{
	public function _j_verifyFormHash(array $a = [])
	{
		if (!isset($a[0])) {
			throw new \Exception('Error: Hash is Required.');
		}
		$oFormBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'r']);
		$oFormBuilder->select([
			'id',
		]);
		$oFormBuilder->where('access_hash', $a[0]);
		$oFormExist = $oFormBuilder->get()->getRow();
		if (!$oFormExist) {
			throw new \Exception('Error: Invoice not found.');
		}
		$oFormBuilder->select([
			'invoice.id',
			'invoice.invoice_no',
			'invoice.currency',
			'invoice.issue_date',
			'invoice.invoice_due',
			'invoice.invoice_due_in',
			'invoice.item_desciption',
			'invoice.quantity',
			'invoice.amount',
			'invoice.discount_rate',
			'invoice.sub_total',
			'invoice.discount_name',
			'invoice.discount_amount',
			'invoice.total',
			'invoice.status',
			'invoice.footer',
			'client.name as client_name',
			'client.addr1 as client_addr1',
			'client.email as client_email',
			'client.mobile_contact as client_mobile',
			'client.company as client_company',
			'admin.name as admin_name',
			'admin.email as admin_email',
			'settings.invoice_logo as invoice_logo',
			'settings.company as admin_company',
			'settings.mobile as admin_mobile',
			'settings.street1 as admin_addr1',
			'settings.footer_email as footer_email',
			'settings.logo_size as logo_size',
		]);
		$oFormBuilder->where('access_hash', $a[0])
			->join('client', 'invoice.client_id=client.id', 'both')
			->join('admin', 'client.created_by=admin.id', 'both')
			->join('settings', 'settings.admin_id=admin.id', 'both');
		$oData = $oFormBuilder->get()->getRow();
			$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $oData->issue_date));	
		// if ($oData->invoice_due == 6) {
		// 	$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $oData->invoice_due_in));
		// } else {
		// 	$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $oData->issue_date)) . ' + ' . $oData->invoice_due . 'day'));
		// }
		$oData->invoice_due_in = dateConvert(array('rDate' => 'M dd yyyy', 'date' => $dueDate));
		$oData->issue_date = dateConvert(array('rDate' => 'M dd yyyy', 'date' => $oData->issue_date, 'gDate' => true));
		$oData->item_desciption = json_decode($oData->item_desciption, true);
		$oData->quantity = json_decode($oData->quantity, true);
		$oData->amount = json_decode($oData->amount, true);
		if ($oData->discount_amount) {
			$oData->discount = true;
		} else {
			$oData->discount = false;
		}
		return ['data' => $oData];
	}
}

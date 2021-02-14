<?php

namespace App\Controllers\Api\Pu;

use CodeIgniter\API\ResponseTrait;

class Put extends BaseController
{
	use ResponseTrait;
	function _j_invoiceView()
	{
		$aPut = $this->request->getRawInput();
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'u']);
		$oInvoiceBuilder->skipValidation();
		$oInvoiceBuilder->select([
			'invoice.invoice_no',
			'invoice.status',
			'settings.company as admin_name',
			'admin.id as user_id',
			'admin.email as admin_email',
			'invoice.access_hash'
		])->where('invoice.id', $aPut['id'])
			->join('admin', 'invoice.created_by=admin.id', 'both')
			->join('settings', 'settings.admin_id=admin.id', 'both');
		$oData = $oInvoiceBuilder->get()->getRow();
		if ($oData->status == 1) {
			$oInvoiceBuilder->where('id', $aPut['id']);
			$oInvoiceBuilder->set(array('status' => 2));
			$oInvoiceBuilder->update();
			sendEmail([
				'e'	 => 'invoiceView',
				'to' =>  $oData->admin_email,
				'invoice_no' => $oData->invoice_no,
				'admin_name' => $oData->admin_name,
				'access_hash' => $oData->access_hash,
				'user_id' => $oData->user_id,
			]);
		}
		return [];
	}

}

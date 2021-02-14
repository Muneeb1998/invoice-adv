<?php

namespace App\Controllers\Api\Pr;

use App\Libraries\DataTable;
use App\Libraries\PdfGenerate;
use Exception;
use phpDocumentor\Reflection\Types\Float_;

class Get extends BaseController
{
	function _j_admin()
	{

		$oDt = new DataTable([
			'modelHelper' => $this->modelHelper,
			'aVars' 			=> $this->aVars,
			'request' 		=> $this->request
		]);
		return $oDt->g([
			'sTable' 		=> 'admin',
			'aCols' 		=> [
				'"ab"',
				'name',
				'email',
				'role',
				'identity',
				'id',
			],
			'fnSetCol' => [$this, 'formData'],
			// 'getCompiledSelect' => true
		]);
	}
	public function formData(array $a)
	{
		$aData = [];
		foreach ($a as $k => $v) {
			foreach ($v as $k1 => $v1) {
				if ($k1 == 'role') {
					if ($v1 == 'sa') {
						$v[$k1] = 'Super Admin';
					}
					if ($v1 == 'a') {
						$v[$k1] = 'Admin';
					}
				}
			}
			$aData[$k] = array_values((array)$v);
		}
		return $aData;
	}
	function _j_client()
	{

		$oDt = new DataTable([
			'modelHelper' => $this->modelHelper,
			'aVars' 			=> $this->aVars,
			'request' 		=> $this->request
		]);
		return $oDt->g([
			'sTable' 		=> 'client',
			'aCols' 		=> [
				'id as check_box',
				'name',
				'company',
				'email',
				'country',
				'mobile_contact',
				'id',
			],
			'aWhere' => [
				['created_by', $this->session->get('userId')]
			]
			//'getCompiledSelect' => true
		]);
	}
	function _j_getClientData()
	{
		$aData = array();
		$oClient = $this->modelHelper->getBuilder('client');
		$oClient->skipValidation();
		$records = $oClient->select([
			'id',
			'name',
			'company',
			'addr1',
			'addr2',
			'city',
			'state',
			'zip',
			'country',
			'email',
			'web_addr',
			'phone_contact',
			'mobile_contact',
			'fax',
		]);
		if (isset($_GET['id'])) {
			$oClient->where('id', $_GET['id']);
		}
		$records = $oClient->get()->getRow();
		if (isset($this->aVars['view'])) {
			$aLabel = array(
				'id',
				'Name',
				'Company Name',
				'Street Address 1',
				'Street Address 2',
				'City',
				'State',
				'Zip Code',
				'Country',
				'Email',
				'Web Adress',
				'Phone Number',
				'Mobile Number',
				'fax'
			);
			$aData = array();
			$i = 0;
			foreach ($records as $k => $v) {
				$aData[$aLabel[$i]] = $v;
				$i++;
			}
			$aRes['aData'] = $aData;
		} else {
			$aRes['aData'] = $records;
		}
		return $aRes;
	}
	function _j_getDashboardData()
	{
		$aRes = array();
		$oAdmin = $this->modelHelper->getBuilder('admin');
		$oAdmin->skipValidation();
		$records = $oAdmin->select([
			'admin.last_login_at',
		])
			->where('admin.id', $this->session->get('userId'));
		$aLastLogin = $records->get()->getResultArray(); //getCompiledSelect();//findAll(); //->getRow();
		// 
		// $oAdmin = $this->modelHelper->getBuilder('inv');
		// $oAdmin->skipValidation();
		// $records = $oAdmin->select([
		// 	'invoice.updated_at',
		// 	'admin.last_login_at',
		// ])
		// 	->where('admin.id', $this->session->get('userId'))
		// 	->where('invoice.deleted_at is NULL')
		// 	->where('invoice.created_by', $this->session->get('userId'))
		// 	->where('invoice.status', 2)
		// 	->orderBy('invoice.id', 'DESC');
		// $aData = $records->get()->getResultArray(); //getCompiledSelect();//findAll(); //->getRow();
		// 
		// ->limit(2);
		if (!isset($_GET['row'])) {
			$_GET['row'] = 2;
		}
		$aStatus = array(
			'0' => 'Draft',
			'1' => 'Sent',
			'2' => 'Viewed',
			'3' => 'Paid',
			'11' => 'Archieve',
		);
		$oInvoice = $this->modelHelper->getBuilder('invoice');
		$oInvoice->skipValidation();
		$records = $oInvoice->select([
			'invoice.invoice_no',
			'invoice.created_at',
			'invoice.updated_at',
			'invoice.status',
			'client.name',
		])
			->where('invoice.deleted_at is NULL')
			->whereIn('invoice.status',[0,1,2,11])
			->groupBy('invoice.id')
			->join('client', 'invoice.created_by=client.created_by')
			->where('invoice.created_by', $this->session->get('userId'))
			->orderBy('invoice.id', 'DESC');
		$aInvoiceData = $records->findAll(); //getCompiledSelect();//findAll(); //->getRow();
		// print_r($aInvoiceData);
		// die();
		$oPayment = $this->modelHelper->getBuilder('payment');
		$oPayment->skipValidation();
		$records = $oPayment->select([
			'payment.created_at',
			'client.name',
			'invoice.invoice_no',
		])
			->join('invoice', 'invoice.id=payment.invoice_id')
			->join('client', 'client.id=payment.client_id')
			->groupBy('payment.id')
			->where('invoice.created_by', $this->session->get('userId'))
			->orderBy('payment.id', 'DESC');
		$aPaymentData = $records->findAll(); //getCompiledSelect();//findAll(); //->getRow();
		// ->limit(1);
		for ($i = 0; $i < $_GET['row']; $i++) {
			if (isset($aLastLogin[$i]['last_login_at'])) {
				$aLastLogin[$i]['last_login_at'] = $aLastLogin[$i]['last_login_at'];
				$aLoginAct =  $this->diffBtwTime($aLastLogin[$i]['last_login_at']);
				if ($aLoginAct['m'] <= 1) {
					$aActivityData[$i]['loginDiffer'] = 'Last Login -Just Now';
				} else {
					if ($aLoginAct['h'] == "00") {
						$time = $aLoginAct['m'] . ' minutes ago';
					} else {
						$time = $aLoginAct['h'] . ' hours ' . $aLoginAct['m'] . ' minutes ago';
					}
					$aActivityData[$i]['loginDiffer'] = 'Last Login ' . ' - ' . $time;
				}
			}
			if (isset($aInvoiceData[$i]['invoice_no'])) {
            // die();
				if ($aInvoiceData[$i]['status'] != 3) {
					if ($aInvoiceData[$i]['status'] == 2 || $aInvoiceData[$i]['status'] == 1) {
						$aInvoicAct =  $this->diffBtwTime($aInvoiceData[$i]['updated_at']);
					} else {
						$aInvoicAct =  $this->diffBtwTime($aInvoiceData[$i]['created_at']);
					}
					if ($aInvoicAct['m'] < 1 && $aInvoicAct['h'] == "00" && $aInvoicAct['d'] == 0) {
						$aActivityData[$i]['invoiceDiffer'] = $aStatus[$aInvoiceData[$i]['status']] . ' Invoice# ' . ucwords($aInvoiceData[$i]['invoice_no']) . ' -Just Now';
					} else {
						if ($aInvoicAct['h'] == "00") {
							$time = $aInvoicAct['m'] . ' minutes ago';
						} elseif ($aInvoicAct['d'] == "0") {
							$time = $aInvoicAct['h'] . 'hour(s) ' . $aInvoicAct['m'] . ' minutes ago';
						} else {
							$time = $aInvoicAct['d'] . ' day(s) ' . $aInvoicAct['h'] . ' hour(s) ' . $aInvoicAct['m'] . ' minutes ago';
						}
						$aActivityData[$i]['invoiceDiffer'] = $aStatus[$aInvoiceData[$i]['status']] . ' Invoice# ' . ucwords($aInvoiceData[$i]['invoice_no']) . ' - ' . $time . '';
					}
				}
			}
			if (isset($aPaymentData[$i]['created_at'])) {
                // print_r($aPaymentData[$i]);
                // die();
				$aPaymentAct =  $this->diffBtwTime($aPaymentData[$i]['created_at']);
				if ($aPaymentAct['m'] <= 1 && $aPaymentAct['h'] == "00" && $aPaymentAct['d'] == 0) {
					$aPaymentActivity[$i]['paymentDiffer'] = ucwords($aPaymentData[$i]['name']) . ' paid invoice# ' . ucwords($aPaymentData[$i]['invoice_no']) . ' -Just Now';
				} else {
					if ($aPaymentAct['h'] == "00") {
						$time = $aPaymentAct['m'] . ' minutes ago';
					} elseif ($aPaymentAct['d'] == "0") {
						$time = $aPaymentAct['h'] . 'hour(s) ' . $aPaymentAct['m'] . ' minutes ago';
					} else {
						$time = $aPaymentAct['d'] . ' day(s) ' . $aPaymentAct['h'] . ' hour(s) ' . $aPaymentAct['m'] . ' minutes ago';
					}
					$aPaymentActivity[$i]['paymentDiffer'] = ucwords($aPaymentData[$i]['name']) . ' paid invoice# ' . ucwords($aPaymentData[$i]['invoice_no']) . ' - ' . $time . '';
				}
			}
		}
		// die();
		if (isset($_GET['action']) && $_GET['action'] != '') {
			if ($_GET['action'] == 'activites') {
				$aRes['activity'] = $aActivityData;
				return ['data' => $aRes];
			}
			if ($_GET['action'] == 'paid') {
				$aRes['paymentActivity'] = isset($aPaymentActivity) ? $aPaymentActivity : [];
				return ['data' => $aRes];
			}
		}
		// print_r($aActivityData);
		$oAdmin = $this->modelHelper->getBuilder('admin');
		$oAdmin->skipValidation();
		$oAdmin = $oAdmin->select([
			'(Select SUM(invoice.total) from invoice where status = 3 and created_by = ' . $this->session->get('userId') . ' and deleted_at is NULL and YEAR(created_at) = ' . date('Y') . ') as `total-invoice`',
			'(Select count(client.id) from client where created_by = ' . $this->session->get('userId') . ' and deleted_at is NULL) as `total-client` ',
			'(Select count(invoice.id) from invoice where status = 2 and created_by = ' . $this->session->get('userId') . ' and deleted_at is NULL)as `unpaid-invoice`',
			'(Select SUM(invoice.total) from invoice where status = 3 and created_by = ' . $this->session->get('userId') . '  and deleted_at is NULL)as `paid-invoice`',
		])
			->where('admin.id', $this->session->get('userId'));
		$aAdminData = $oAdmin->findAll();
		$aAdminData[0]['total-invoice'] = number_format($aAdminData[0]['total-invoice'], 2) . ' USD';
		$aAdminData[0]['paid-invoice'] = number_format($aAdminData[0]['paid-invoice'], 2) . ' USD';
		$aAdminData[0]['total-client'] = $aAdminData[0]['total-client'] . ' CLIENT(s)';
		$oInvoice = $this->modelHelper->getBuilder('invoice');
		$oInvoice->skipValidation();
		$oInvoice = $oInvoice->select([
			'total',
			'issue_date',
			'invoice_due',
			'invoice_due_in',
		])->where('created_by', $this->session->get('userId'))
			->where('status', 2);
		$aInvoiceData = $oInvoice->findAll();
		$totalDue = 0.00;
		foreach ($aInvoiceData as $k => $v) {
			if ($v['invoice_due'] == 6) {
				$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $v['invoice_due_in']));
				// $dueDate = $aPost['invoice_due_in'];
			} else {
				$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aInvoiceData[0]['issue_date'])) . ' + ' . $aInvoiceData[0]['invoice_due'] . 'day'));
			}
			$today = date('Y-m-d');
			if ($today > $dueDate) {
				$totalDue = $totalDue + number_format((float)$v['total'], 2, '.', '');
			}
			// echo number_format((float)$v['total'], 2, '.', '');
		}
		$aAdminData[0]['unpaid-invoice'] = number_format($totalDue, 2) . ' USD';
		// print_r(number_format($totalDue, 2));
		// die();
		// states
		$aRes = array();
		$oInvoiceBuilder = $this->modelHelper->getBuilder('invoice');
		$oInvoiceBuilder->skipValidation();
		$oInvoiceBuilder->select(['id'])
			->where('invoice.created_by', $this->session->get('userId'));
		// ->where('user_quote_request.id', $aPost['id']);
		$aRes['donut']['total'] = count($oInvoiceBuilder->findAll()); //->getCompiledSelect();
		$oInvoiceBuilder->select(['id'])
			->where('invoice.created_by', $this->session->get('userId'))
			->where('invoice.status', 0);
		// ->where('user_quote_request.id', $aPost['id']);
		$aRes['donut']['draft'] = count($oInvoiceBuilder->findAll()); //->getCompiledSelect();
		$oInvoiceBuilder->select(['id'])
			->where('invoice.created_by', $this->session->get('userId'))
			->where('invoice.status', 1);
		// ->where('user_quote_request.id', $aPost['id']);
		$aRes['donut']['sent'] = count($oInvoiceBuilder->findAll()); //->getCompiledSelect();
		$oInvoiceBuilder->select(['id'])
			->where('invoice.created_by', $this->session->get('userId'))
			->where('invoice.status', 2);
		$aRes['donut']['unpaid'] = count($oInvoiceBuilder->findAll()); //->getCompiledSelect();
		$oInvoiceBuilder->select(['id'])
			->where('invoice.created_by', $this->session->get('userId'))
			->where('invoice.status', 3);
		$aRes['donut']['paid'] = count($oInvoiceBuilder->findAll()); //->getCompiledSelect();
		// 
		$oPaidBuilder = $this->modelHelper->getBuilder('payment');
		$oPaidBuilder->skipValidation();
		$aMonth = array();
		$oPaidBuilder->select(['created_at', 'payment_amount'])
			->where("created_at > now() - INTERVAL 12 month");
		$aRecord = $oPaidBuilder->findAll();
		for ($i = 0; $i < 12; $i++) {
			$aMonth[date("m", strtotime('-' . $i . ' month'))] = 0;
		}
		$aPaid = $aMonth;
		$aUnpaid = $aMonth;
		// print_r($aPaid);
		// die();
		$aPaid = array_reverse($aPaid, true);
		$aUnpaid = array_reverse($aUnpaid, true);
		foreach ($aRecord as $k => $v) {
			$timestamp = strtotime($v['created_at']);
			$date = date("m", $timestamp);
			$aPaid[$date]  = $aPaid[$date] + floatval($v['payment_amount']);
		}
		$oInvoiceBuilder->skipValidation();
		$oInvoiceBuilder->select(['unpaid_at', 'total'])
			->where('unpaid_at > now() - INTERVAL 12 month');
		$aRecord = $oInvoiceBuilder->findAll();
		foreach ($aRecord as $k => $v) {
			$timestamp = strtotime($v['unpaid_at']);
			$date = date("m", $timestamp);
			$aUnpaid[$date]  = $aUnpaid[$date] + floatval($v['total']);
		}
		// $aDayLabel = array();
		// for ($i = 0; $i <= 30; $i++) {
		//     $aDayLabel[] = date("d-M", strtotime('-' . $i . ' days'));
		// }
		$check = array_filter($aRes);
		$aRes['success'] = true;
		if (empty($check)) {
			$aRes['success'] = false;
		}
		// echo $aAdminData;
		// die();
		$aRes['count'] = $aAdminData[0];
		$aRes['paid'] = array_values($aPaid);
		$aRes['unpaid'] = array_values($aUnpaid);
		$aRes['paymentActivity'] = isset($aPaymentActivity) ? $aPaymentActivity : [];
		$aRes['activity'] = $aActivityData;
		return ['data' => $aRes];
		// print_r($aClientData);
		// die();
	}
	function _j_clientList()
	{
		$aRes = array();
		$aData = $this->request->getGet();
		$search_parameter = (array_key_exists('term', $aData)) ? $aData['term'] : "";
		$oClient = $this->modelHelper->getBuilder('client');
		$oClient->skipValidation();
		$oClient->select([
			// 'count(client.id) as total',
			'client.id',
			'client.name',
			'client.email',
			'client.is_archive',
			'client.mobile_contact as mobile'
		])->where('created_by', $this->session->get('userId'));
		$oClient->groupStart();
		$oClient->orLike('name', $search_parameter);
		$oClient->orLike('email', $search_parameter);
		$oClient->orLike('company', $search_parameter);
		$oClient->groupEnd();
		$query =  $oClient->findAll();
		$total = $oClient->countAllResults();
		$aRes['total_count'] = $total;
		$aRes["incomplete_results"] = false;
		$aRes['items'] = $query;
		// return ['data' => $aRes];
		return $aRes;

		// 		$oClient = $this->modelHelper->getBuilder('client');
		// 		$oClient->skipValidation();
		// 		$records = $oClient->select([
		// 			// 'count(client.id) as total',
		// 			'client.id',
		// 			'client.name',
		// 			'client.email',
		// 			'client.mobile_contact as mobile'
		// 		])->where('created_by', $this->session->get('userId'));
		// 		$aClientData = $records->findAll();
		// 		return ['data' => $aClientData];
	}
	public function _j_settingData()
	{
		$oSetting = $this->modelHelper->getBuilder('settings');
		$oSetting->skipValidation();
		$records = $oSetting->select([
			'settings.company',
			'settings.paypal_email',
			'settings.mobile',
			'settings.invoice_pattern',
			'settings.footer_email',
			'settings.street1',
			'settings.street2',
			'settings.city',
			'settings.state',
			'settings.zip_code',
			'settings.country',
			'settings.logo_size',
		])->where('admin_id', $this->session->get('userId'));
		$aClientData = $records->get()->getRow();
		if (!$aClientData) {
			throw new \RuntimeException();
		}
		return ['data' => $aClientData];
	}
	public function _j_invoiceNo()
	{
		$oSetting = $this->modelHelper->getBuilder('settings', ['act' => 'r']);
		$oSetting->skipValidation();
		$oSetting->select([
			'invoice_pattern',
			'logo_size',
			'footer_email'
		])->where('admin_id', $this->session->get('userId'));
		$invoicePattern = $oSetting->get()->getResultArray();
		$footerEmail = $invoicePattern['0']['footer_email'];
		$logoSize = $invoicePattern['0']['logo_size'];
		$invoicePattern = $invoicePattern['0']['invoice_pattern'];
		$oInvoice = $this->modelHelper->getBuilder('invoice', ['act' => 'r']);
		$oInvoice->skipValidation();
		$oInvoice->select([
			'Max(id) as id',
		])->where('created_by', $this->session->get('userId'));
		$maxId = $oInvoice->get()->getResultArray();
		$maxId = $maxId[0]['id'];
		$oInvoice->select([
			'invoice_no',
		])->where('created_by', $this->session->get('userId'))
			->where('id', $maxId);
		$invoiceNo = $oInvoice->get()->getResultArray();
		$invoiceNo = $invoiceNo[0]['invoice_no'];
		$lastInvoiceNo = explode('-', $invoiceNo);
		$nextInvoiceNo = (int)$lastInvoiceNo[1] + 1;
		return ['data' => array(
			'invoice_no' => $invoicePattern . $nextInvoiceNo,
			'footer_email' => $footerEmail,
			'logo_size' => $logoSize
		)];
		// $this->aVars['registration_no'] = $idPre . ($aStaffId[0]['registration_no'] + 1);
	}
	public function _j_invoice()
	{
		// print_r($_GET['status']);
		// die();
		$oDt = new DataTable([
			'modelHelper' => $this->modelHelper,
			'aVars' 			=> $this->aVars,
			'request' 		=> $this->request
		]);
		$aDt = array(
			'sTable' 		=> 'invoice',
			'aCols' 		=> [
				'invoice.id as check_box',
				'client.name',
				'client.company',
				'invoice.invoice_no',
				'CONCAT(invoice.total," ",invoice.currency) as total',
				'invoice.issue_date',
				'invoice.invoice_due',
				'invoice.invoice_due_in',
				'invoice.status',
				'invoice.id',
				'client.is_archive',
			],
			'aJoin' => [
				['client', 'invoice.client_id=client.id', 'both'],
			],
			'aWhere' => [
				['invoice.created_by', $this->session->get('userId')]
			],
			'order' => 'asc',
			// 'getCompiledSelect' => true,
			'fnSetCol' => [$this, 'invoiceData']
		);
		// 		echo $this->aVars['status'];
		//var_dump($this->aVars['status'] != 'all');
		// 		die();
		if ($_GET['status'] != 'all' && $_GET['status'] != '2') {
			$aDt['aWhere'][] =
				[
					'status', $_GET['status'],
				];
		}
		if ($_GET['status'] == '2') {
			$aDt['aWhereIn'][] = ['status', array('1', '2')];
		}
		return $oDt->g($aDt);
	}
	public function invoiceData(array $a)
	{
		$aStatus = array(
			'0' => 'Draft',
			'1' => 'Sent',
			'2' => 'UnPaid',
			'3' => 'Paid',
			'11' => 'Archieve',
		);
		$aData = [];
		foreach ($a as $k => $v) {
			if ($v['invoice_due'] == 6) {
				$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $v['invoice_due_in']));
			} else {
				$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $v['issue_date'])) . ' + ' . $v['invoice_due'] . 'day'));
			}
			$currentData  = date('Y-m-d');
			if ($dueDate < date('Y-m-d') &&  ($_GET['status'] == 2 || $_GET['status'] == 11)) {
				$v['status'] = 'OverDue';
			} else {
				$v['status'] = $aStatus[$v['status']];
			}
			unset($v['invoice_due']);
			$v['invoice_due_in'] = $dueDate;
			$v['issue_date'] = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $v['issue_date']));
			$aData[$k] = array_values((array)$v);
			// 			if ($_GET['status'] == 11) {
			// 			 //   var_dump($v['is_archive'] == 1);
			// 				if ($v['is_archive'] == 1) {
			// 					unset($v['is_archive']);
			// 					$aData[$k] = array_values((array)$v);
			// 				}
			// 			}else{
			// 				unset($v['is_archive']);
			// 			}
		}
		// print_r($aData);
		// die();
		return $aData;
	}
	public function _j_getInvoiceData()
	{
		$aData = array();
		$oInvoice = $this->modelHelper->getBuilder('invoice');
		$oInvoice->skipValidation();
		$records = $oInvoice->select([
			'invoice.invoice_no',
			'client.name',
			'invoice.issue_date',
			'invoice.invoice_due',
			'invoice.invoice_due_in',
			'invoice.item_desciption',
			'invoice.quantity',
			'invoice.amount',
			'invoice.discount_rate',
			'CONCAT(invoice.total," ",invoice.currency) as total',
		])->where('invoice.id', $this->aVars['id'])
			->join('client', 'invoice.client_id=client.id', 'both');
		$records = $oInvoice->get()->getRow();
		if (isset($this->aVars['view'])) {
			$aLabel = array(
				'invoice #',
				'Client Name',
				'Issue Date',
				'Due In',
				'Due Date',
				'Desciption',
				'Quantity',
				'Amount',
				'Discount',
				'Total',
			);
			$aData = array();
			$i = 0;
			if ($records->invoice_due == 6) {
				unset($records->invoice_due);
				unset($aLabel[3]);
			} else {
				unset($records->invoice_due_in);
				unset($aLabel[4]);
				$records->invoice_due = $records->invoice_due . ' days';
			}
			$aLabel = array_values($aLabel);
			foreach ($records as $k => $v) {
				$aData[$aLabel[$i]] = $v;
				$i++;
			}
			$aData['Desciption'] = json_decode($aData['Desciption'], true);
			$aData['Quantity'] = json_decode($aData['Quantity'], true);
			$aData['Amount'] = json_decode($aData['Amount'], true);
			$aRes['aData'] = $aData;
		} else {
			$aRes['aData'] = $records;
		}
		return $aRes;
	}
	public function _j_invoiceEditData($id)
	{
		$oInvoice = $this->modelHelper->getBuilder('invoice');
		$oInvoice->skipValidation();
		$records = $oInvoice->select([
			'client.id as client_name',
			'invoice.invoice_no',
			'invoice.currency',
			'admin.name adminName',
			'admin.email adminEmail',
			'invoice.issue_date',
			'invoice.invoice_due',
			'invoice.invoice_due_in',
			'client.name as clientName',
			'client.email as clientEmail',
			'client.mobile_contact as clientMobile',
			'invoice.item_desciption',
			'invoice.quantity',
			'invoice.amount',
			'invoice.discount_name',
			'invoice.discount_rate',
			'invoice.discount_amount',
			'invoice.sub_total',
			'invoice.total',
			'invoice.footer',
			'invoice.status',
		])->where('invoice.id', $id)
			->join('client', 'invoice.client_id=client.id', 'both')
			->join('admin', 'invoice.created_by=admin.id', 'both');
		$records = $oInvoice->get()->getRow();
		if (!$records) {
			throw new \RuntimeException('Invoice not found');
		}
		if ($records->discount_name) {
			$records->discount = '1';
		} else {
			$records->discount = '0';
		}
		return ['data' => $records];
	}
	public function _j_smtpData()
	{
		$oSmtp = $this->modelHelper->getBuilder('SmtpSettings');
		$oSmtp->skipValidation();
		$records = $oSmtp->select([
			'smtpHost',
			'smtpUsername',
			'smtpPass',
			'smtpPort',
			'bcc',
			'replyTo',
			'setFromEmail',
			'setFromName',
			'SMTPSecure',
			'auth',
		])->where('admin_id', $this->session->get('userId'));
		$records = $oSmtp->get()->getRow();
		if (!$records) {
			throw new \RuntimeException();
		}
		return ['data' => $records];
	}
	public function _j_pdfData()
	{
		$oFormBuilder = $this->modelHelper->getBuilder('invoice', ['act' => 'r']);
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
		]);
		$oFormBuilder->whereIn('invoice.id', $_GET['id'])
			->join('client', 'invoice.client_id=client.id', 'both')
			->join('admin', 'client.created_by=admin.id', 'both')
			->join('settings', 'settings.admin_id=admin.id', 'both');
		$aData = $oFormBuilder->get()->getResultArray();
		foreach ($aData as $k => $v) {
			if ($aData[$k]['invoice_due'] == 6) {
				$dueDate = dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aData[$k]['invoice_due_in']));
			} else {
				$dueDate = date('Y-m-d', strtotime(dateConvert(array('rDate' => 'yyyy-mm-dd', 'date' => $aData[$k]['issue_date'])) . ' + ' . $aData[$k]['invoice_due'] . 'day'));
			}
			$aData[$k]['invoice_due_in'] = dateConvert(array('rDate' => 'M dd yyyy', 'date' => $dueDate));
			$aData[$k]['issue_date'] = dateConvert(array('rDate' => 'M dd yyyy', 'date' => $aData[$k]['issue_date'], 'gDate' => true));
			$aData[$k]['item_desciption'] = json_decode($aData[$k]['item_desciption'], true);
			$aData[$k]['quantity'] = json_decode($aData[$k]['quantity'], true);
			$aData[$k]['amount'] = json_decode($aData[$k]['amount'], true);
			if ($aData[$k]['discount_amount']) {
				$aData[$k]['discount'] = true;
			} else {
				$aData[$k]['discount'] = false;
			}
		}
		$oZip = new PdfGenerate($aData);
		return $oZip->pdfToZip();
	}
	public function _j_adminData()
	{
		$oAdmin = $this->modelHelper->getBuilder('admin');
		$oAdmin->skipValidation();
		$records = $oAdmin->select([
			'admin.id',
			'admin.name',
			'admin.email',
		])->where('id', $_GET['id']);
		$aoAdminData = $records->get()->getRow();
		if (!$aoAdminData) {
			throw new \RuntimeException('Admin not found');
		}
		return ['data' => $aoAdminData];
	}
	private function diffBtwTime($passedTime)
	{
		$current_time = new \DateTime(date("Y-m-d H:i:s"));
		$passedTime = new \DateTime($passedTime);
		$interval = $passedTime->diff($current_time);
		$diffDay = $interval->format("%d%");
		$diffHour = $interval->format("%H%");
		$diffMin = $interval->format("%i%");
		return array(
			'd' => $diffDay,
			'h' => $diffHour,
			'm' => $diffMin,
		);
	}
}

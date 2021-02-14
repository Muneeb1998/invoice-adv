<?php

namespace App\Controllers;

class ClientInvoice extends BaseController
{
	public function index($sHash = false)
	{
		if (!$sHash) {
			return redirect()
				->to(site_url('notfound'));
		}
	   // die('t');
		session_write_close();
		$client = \Config\Services::curlrequest();
		$res = $client->get(API_URL . 'pu/verifyFormHash/' . $sHash);
		$aRes = json_decode($res->getBody(), true);

		if (!$aRes['success']) {
			return redirect()
				->to(site_url('notfound'));
		}
		$aData['aInvoiceData'] =  $aRes['data'];
		$aData['hash'] =  $sHash;
		$aData['aScriptFile'] = [];
		$aData['aIntCss'][] = '
		.imp {
			font-size: 20px;
			font-weight: 600;
		}
		#paybtn,#download{
			padding: 10px 65px;
			font-size: 20px;
			border-radius: 4px;
		}
		#download{
			padding: 10px 38px !important;
		}
		@media only screen and (max-width: 575px)  {
			.sm-none{
				display:none !important 
			}
			.sm-center{
				text-align: center !important;
			}
			.card{
				padding: 10px 0px 10px 10px !important;
			}
		}
		.card{
			padding: 10px 0px 10px 30px;
		}
		p{
			margin-bottom: 5px !important;
		}
        .heading{
            text-transform: uppercase;
            color: var(--siteBgColor);
        }
	  ';
	  $aData['aExtJs'] = [
		HTTP_ASSETS . 'js/invoice.js'
	  ];
		$aData['aIntJsHdr'][] = '
		var id = "' .$aRes['data']['id'] . '";
		var apiUrl  = "' . API_URL . '";
		var clientData = "'.$aRes['data']['status'].'"
  	';
		$aData['metaTitle'] = 'Invoice';
		return view('default/client', $aData);
	}
}

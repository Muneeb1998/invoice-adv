<?php
namespace App\Controllers\Api\Pr;
class Delete extends BaseController
{
	function _j_deleteClient()
	{

		$aData = $this->request->getRawInput();
		$aRes = array();
		$oClient = $this->modelHelper->getBuilder('client');
		$oClient->cleanRules();
		if($aData['action'] == 'multiple'){
			$oClient->whereIn('id', $aData['id']);
		}else{
			$oClient->where('id', $aData['id']);
		}
		$oClient->delete();
		return ['data' => $aRes];
	}
	function _j_deleteInvoice()
	{
		$aData = $this->request->getRawInput();
		$aRes = array();
		$oInvoice = $this->modelHelper->getBuilder('invoice');
		$oInvoice->cleanRules();
		if($aData['action'] == 'multiple'){
			$oInvoice->whereIn('id', $aData['id']);
		}else{
			$oInvoice->where('id', $aData['id']);
		}
		$oInvoice->delete();
		return ['data' => $aRes];
	}
    function _j_deleteAdmin(){
        $aData = $this->request->getRawInput();
		$aRes = array();
		$oAdmin = $this->modelHelper->getBuilder('admin');
		$oAdmin->cleanRules();
		$oAdmin->where('id', $aData['id']);
		$oAdmin->delete();
		return ['data' => $aRes];
    }
}
?>
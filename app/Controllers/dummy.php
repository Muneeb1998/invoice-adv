<?php
namespace App\Controllers;

class Login extends BaseController
{
	public function index()
	{
		$aData['aExtJs'] = [];
		$aData['aExtCss'] = [];
		$aData['aIntJs'][] = '';
		return render('home', $aData);
	}
}
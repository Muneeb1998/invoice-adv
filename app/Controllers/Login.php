<?php

namespace App\Controllers;

class Login extends BaseController
{
	public function index()
	{
		if ($this->iUserId = $this->libraryApp->auth()) {
			return redirect()
				->to('dashboard')
				->with('aAlrt', [
					'error',
					lang('Wapp.err.alreadyLoggedIn')
				]);
		}
		$aData['aExtJs'] = [];
		$aData['aIntJsHdr'][] = '
		var siteUrl = "' . site_url() . '";
		var apiUrl  = "' . API_URL . '";
  	';
		$aData['aExtCss'] = [];
		$aData['aIntJs'][] = '
			$(".preloader ").fadeOut();
		';
		return view('default/login', $aData);
	}
}

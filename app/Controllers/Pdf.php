<?php

namespace App\Controllers;
use App\Libraries\PdfGenerate;
class Pdf extends BaseController
{
	public function index($sHash = false)
	{
		if (!$sHash) {
			return redirect()
				->to(site_url('notfound'));
		}
		$oFormBuilder = new PdfGenerate([
            'sHash' => $sHash,
        ]);
        $oFormBuilder->generate();
	}
}
?>
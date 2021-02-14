<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class SmtpSettings extends BaseController
{
    public function index()
    {
        $role =  $this->session->get('role');
        if ((!$this->iUserId = $this->libraryApp->auth())) {
            return redirect()
                ->to('login')
                ->with('aAlrt', [
                    'error',
                    lang('Wapp.err.alreadyLoggedIn')
                ]);
        }
        session_write_close();
        $aSmtpData = [];
        $client = \Config\Services::curlrequest();
        $res = $client->get(API_URL . 'pr/smtpData');
        $aRes = json_decode($res->getBody(), true);
        if ($aRes['success']) {
            $aSmtpData = $aRes['data'];
        } 
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [
            // HTTP_ASSETS . 'plugins/bootstrap-select/css/bootstrap-select.css'
        ];
        $aData['aIntJs'][] = '
        let aSetting = JSON.parse(\''.json_encode($aSmtpData).'\');
			$.each(aSetting, function(k, v){
				switch(k){
					case "auth":
						if(v == "1"){
							$("#"+k).prop("checked", true);
						}else{
							$("#"+k).prop("checked", false);
						}
						break;
					default:
						$("#"+k).val(v);
						break;
				}
			});
        $(document).on("submit", "#mailSettings", function () {
            var self = $(this);
            var oFormData = self.serializeArray();
            $(".preloader").removeClass("d-none")
            $.ajax({
                type: "POST",
                url: apiUrl + "pr/mailSettings",
                dataType: "json",
                data: oFormData,
                success: function (r) {
                    $(".preloader").addClass("d-none")
                    if (r.success) {
                     response("Settings saved succesfully.","success");
                    } else {
                        $(".preloader").addClass("d-none")
                        showValidationError(r);
                    }
                }
            });
            return false
        })
        ';
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('mailSetting');
        return render('smtp/index', $aData);
    }
}

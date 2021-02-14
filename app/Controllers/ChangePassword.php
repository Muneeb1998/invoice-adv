<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class ChangePassword extends BaseController
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
        $aData['aIntJs'][] = '
        
$(document).on("submit", "form#chnagepassword", function () {
        var aData = $(this).serializeArray();
        $(".preloader").removeClass("d-none")
        $.ajax({
            url: apiUrl + "pr/changePassword",
            type: "POST",
            data: aData,
            dataType: "json",
            success: function (r) {
                if (r.success) { 
                    $("#chnagepassword ")[0].reset()
                    $(".preloader ").addClass("d-none")
                    response("Password Changed Successfully", "success");
                } else {
                    $(".preloader ").addClass("d-none")
                    showValidationError(r)
                }
            }
        })
    return false;
})
        ';
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [
            // HTTP_ASSETS . 'plugins/bootstrap-select/css/bootstrap-select.css'
        ];
        $aData['aIntCss'][] = '
        ';
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('password');
        return render('password/index', $aData);
    }
}

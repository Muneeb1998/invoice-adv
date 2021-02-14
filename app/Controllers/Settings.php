<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class Settings extends BaseController
{
    public function index()
    {
        $role =  $this->session->get('role');
        if (!$this->iUserId = $this->libraryApp->auth() || $role == 'a') {
            return redirect()
                ->to(site_url())
                ->with('aAlrt', [
                    'error',
                    lang('Wapp.err.alreadyLoggedIn')
                ]);
        }
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('info');
        $aData['aIntCss'][] = '
         h3.group-titile {
            color: var(--siteColor);
            border: 1px solid;
            padding: 5px 10px !important;
            text-align: center;
        }
        ';
        $aData['aExtJs'] = [
            HTTP_ASSETS . 'js/country.js',
            HTTP_ASSETS . 'js/pages/settings/app.js'
        ];
        session_write_close();
        $client = \Config\Services::curlrequest();
        $res = $client->get(API_URL . 'pr/settingData');
        $aRes = json_decode($res->getBody(), true);
        if ($aRes['success']) {
            $aData['aIntJs'][] = '
            var formId = true;
            var formData = ' . json_encode($aRes['data']) . '
            ';
        } else {
            $aData['aIntJs'][] = '
            var formId = false;
            ';
        }
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [];
        return render('setting/info', $aData);
    }
    // public function info()
    // {
    //     $role =  $this->session->get('role');
    //     if (!$this->iUserId = $this->libraryApp->auth() || $role == 'a') {
    //         return redirect()
    //             ->to(site_url())
    //             ->with('aAlrt', [
    //                 'error',
    //                 lang('Wapp.err.alreadyLoggedIn')
    //             ]);
    //     }
    //     $oFormBuilder = new FormBuilder($this, [
    //         'formDefaults' => 'admin'
    //     ]);
    //     $aData['form'] = $oFormBuilder->getForm('info');
    //     $aData['aIntCss'][] = '
    //      h3.group-titile {
    //         color: var(--siteColor);
    //         border: 1px solid;
    //         padding: 5px 10px !important;
    //         text-align: center;
    //     }
    //     ';
    //     $aData['aExtJs'] = [
    //         HTTP_ASSETS . 'js/country.js',
    //         HTTP_ASSETS . 'js/pages/setting/add.js'
    //     ];
    //     $aData['name'] = ucwords($this->session->get('userName'));
    //     $aData['email'] = strtolower($this->session->get('email'));
    //     $aData['role'] = $role;
    //     $aData['aExtCss'] = [];
    //     $aData['aIntJs'][] = '
    //     $(document).on("submit", "form#info", function () {
    //             $(".preloader").removeClass(" d-none");
    //             var fd = new FormData(this);
    //             $.ajax({
    //                 url: apiUrl + "pr/settings",
    //                 type: "POST",
    //                 data: fd,
    //                 processData: false,
    //                 contentType: false,
    //                 cache: false,
    //                 dataType: "json",
    //                 success: function (r) {
    //                     if (r.success) {
    //                         $(".preloader").addClass("d-none")
    //                         response("Settings has been saved", "success");
    //                         setTimeout(function () { location.reload(); }, 1000);
    //                     } else {
    //                         $(".preloader").addClass("d-none")
    //                         showValidationError(r)
    //                     }
    //                 } 
    //             })
    //         return false;
    //     })';
    //     return render('setting/info', $aData);
    // }
}

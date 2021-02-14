<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class Client extends BaseController
{
    public function index()
    {
        $role =  $this->session->get('role');
        if ((!$this->iUserId = $this->libraryApp->auth()) || $role == 'sa') {
            return redirect()
                ->to('login')
                ->with('aAlrt', [
                    'error',
                    lang('Wapp.err.alreadyLoggedIn')
                ]);
        }
        $aData['aExtJs'] = [
            HTTP_ASSETS . 'js/pages/client/app.js',
            HTTP_ASSETS . 'js/country.js',
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [
            // HTTP_ASSETS . 'plugins/bootstrap-select/css/bootstrap-select.css'
        ];
        $aData['aIntCss'][] = '
        tr th:last-child {
            width: 10% !important;
        }
        .update-body {
            padding: 10px 10px;
        }
        h3.group-titile {
            color: var(--siteColor);
            border: 1px solid;
            padding: 5px 10px !important;
            text-align: center;
        }
        [type="checkbox"].filled-in:not(:checked) + label:after,
        [type="checkbox"].filled-in:checked + label:after,
        [type="checkbox"].filled-in:checked + label:before{
            top: 5px !important;
        }
        ';
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('addClient');
        return render('client/index', $aData);
    }
    public function add()
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
        $aData['form'] = $oFormBuilder->getForm('addClient');
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
            HTTP_ASSETS . 'js/pages/client/add.js'
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [];
        $aData['aIntJs'][] = '';
        return render('client/add', $aData);
    }
}

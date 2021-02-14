<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class Admin extends BaseController
{
    public function index()
    {
        $role =  $this->session->get('role');
        if ((!$this->iUserId = $this->libraryApp->auth()) || $role == 'a') {
            return redirect()
                ->to('login')
                ->with('aAlrt', [
                    'error',
                    lang('Wapp.err.alreadyLoggedIn')
                ]);
        }
        $aData['aExtJs'] = [
            HTTP_ASSETS . 'js/pages/admin/app.js'
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
         $aData['aIntCss'][] = '
            #password{
                    margin-top: 30px;
            }
            button.btn.btn-primary.m-t-15.waves-effect {
                    margin-bottom: 10px;
            }
         ';  
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('addAdmin');
        $aData['aExtCss'] = [
            // HTTP_ASSETS . 'plugins/bootstrap-select/css/bootstrap-select.css'
        ];
        $aData['aIntJs'][] = '';
        return render('admin/index', $aData);
    }
    public function add()
    {
        $role =  $this->session->get('role');
        if ((!$this->iUserId = $this->libraryApp->auth()) || $role == 'a') {
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
        $aData['form'] = $oFormBuilder->getForm('addAdmin');
        $aData['aExtJs'] = [
            HTTP_ASSETS . 'js/pages/admin/add.js?v='.time()
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [];
        $aData['aIntJs'][] = '';
        return render('admin/add', $aData);
    }
}

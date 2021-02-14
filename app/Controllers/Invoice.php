<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;

class Invoice extends BaseController
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
            HTTP_ASSETS . 'js/pages/invoice/app.js',
            HTTP_ASSETS . 'js/country.js',
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [
            // HTTP_ASSETS . 'plugins/bootstrap-select/css/bootstrap-select.css'
        ];
        $aData['aIntCss'][] = '
        [type="checkbox"].filled-in:not(:checked) + label:after,
        [type="checkbox"].filled-in:checked + label:after,
        [type="checkbox"].filled-in:checked + label:before{
            top: 5px !important;
        }
        table tr .material-icons {
            display: inline-flex !important;
            vertical-align: sub !important;
        }
        td {
            padding-top: 6px !important;
        }
        td > * {
             vertical-align: middle;
        }
        .status li {
            display: inline-block;
            background: var(--siteBgColor);
            color: white;
            padding: 10px 30px;
            margin: 0 !important;
            font-weight: 700;
            cursor: pointer;
        }
        .status li.active {
            background: var(--siteColor);
        }
        ';
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin'
        ]);
        $aData['form'] = $oFormBuilder->getForm('addClient');
        return render('invoice/index', $aData);
    }
    public function add($iFormId = false)
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
        session_write_close();
        $client = \Config\Services::curlrequest();
        if ($iFormId) {
            $res = $client->get(API_URL . 'pr/invoiceEditData/' . $iFormId);
            $aRes = json_decode($res->getBody(), true);
            // print_r($aRes);
            // die();
            if ($aRes['success']) {
                $aData['aIntJs'][] = '
                    var form = true;
                    var iformId = ' . $iFormId . ';
                    var data = ' . json_encode($aRes['data']) . ';
                    var companyName = "' . MAIL_NAME . '";
                ';
            } else {
                $aData['aIntJs'][] = '
                var form = false;
                var iformId = "";
                var companyName = "' . MAIL_NAME . '";
            ';
            }
        } else {
            $aData['aIntJs'][] = '
            var form = false;
            var iformId = "";
            var companyName = "' . MAIL_NAME . '";
        ';
        }
        $res = $client->get(API_URL . 'pr/invoiceNo');
        $aRes = json_decode($res->getBody(), true);
        // print_r($aRes);
        // die();
        if ($aRes['success']) {
            $invoiceNo = $aRes['data']['invoice_no'];
            $footerEmail = $aRes['data']['footer_email'];
            $logoSize = $aRes['data']['logo_size'];
        } else {
            $invoiceNo = '';
            $footerEmail = '';
            $logoSize = '';
        }
        $oFormBuilder = new FormBuilder($this, [
            'formDefaults' => 'admin',
            'name' => ucwords($this->session->get('userName')),
            'email' => strtolower($this->session->get('email')),
            'userId' => $this->session->get('userId'),
            'invoiceNo' => $invoiceNo,
            'footerEmail' => $footerEmail,
            'logoSize' => $logoSize
        ]);
        $aData['form'] = $oFormBuilder->getForm('addInvoice');
        $aData['aIntCss'][] = '
        h3.group-titile {
            color: var(--siteColor);
            border: 1px solid;
            padding: 5px 10px !important;
            text-align: center;
        }
        #addItem{
            padding: 10px 70px;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .delete,.add-discount,del-discount{
            cursor: pointer;
        }
        ';
        $aData['aExtJs'] = [
            HTTP_ASSETS . 'js/currency.js',
            HTTP_ASSETS . 'js/pages/invoice/add.js'
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $role;
        $aData['aExtCss'] = [];
        return render('invoice/add', $aData);
    }
}

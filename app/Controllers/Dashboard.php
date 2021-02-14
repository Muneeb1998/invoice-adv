<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!$this->iUserId = $this->libraryApp->auth()) {
            return redirect()
                ->to('login')
                ->with('aAlrt', [
                    'error',
                    lang('Wapp.err.alreadyLoggedIn')
                ]);
        }
        $aData['aExtJs'] = [
            HTTP_ASSETS . "plugins/raphael/raphael.min.js",
            HTTP_ASSETS . "plugins/morrisjs/morris.js",
            HTTP_ASSETS . "plugins/chartjs/Chart.bundle.js",
            HTTP_ASSETS . "plugins/flot-charts/jquery.flot.js",
            HTTP_ASSETS . "plugins/flot-charts/jquery.flot.resize.js",
            HTTP_ASSETS . "plugins/flot-charts/jquery.flot.pie.js",
            HTTP_ASSETS . "plugins/flot-charts/jquery.flot.categories.js",
            HTTP_ASSETS . "plugins/flot-charts/jquery.flot.time.js",
            HTTP_ASSETS . "plugins/jquery-sparkline/jquery.sparkline.js",
            HTTP_ASSETS . 'js/pages/dashboard/app.js'
        ];
        $aData['name'] = ucwords($this->session->get('userName'));
        $aData['email'] = strtolower($this->session->get('email'));
        $aData['role'] = $this->session->get('role');
        $aData['aExtCss'] = [
            HTTP_ASSETS . "plugins/morrisjs/morris.css",
        ];
        $aData['aIntCss'][] = '
            .status{
                border: 1px solid;
                text-align: center;
            }
            .count .info-box{
                height: 60px !important;
                margin-bottom: 3px !important;
            }
            .count .icon{
                height: 60px !important;
            }
            .count i{
                font-size: 35px !important;
                line-height: 60px !important;
            }
            .count .content div {
                color: white !important;
            }
            p.acts {
                border-bottom: 1px solid #0003;
                padding-bottom: 5px;
            }
        ';
        $aData['aIntJs'][] = '';
        return render('dashboard', $aData);
    }
}

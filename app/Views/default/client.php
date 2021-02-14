<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo 'Invoice - ' . APP_NAME; ?></title>
    <!-- Load external Css files -->
    <?php if (isset($aExtCss)) : ?>
        <?php foreach ($aExtCss as $v) : ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $v; ?>">
        <?php endforeach ?>
    <?php endif; ?>
    <!-- Load external Css files; -->
    <link rel="stylesheet" type="text/css" href="<?php echo HTTP_ASSETS; ?>css/style.min.css">

    <!-- Load inline Css -->
    <?php if (isset($aIntCss)) : ?>
        <style type="text/css">
            <?php foreach ($aIntCss as $v) : ?><?php echo $v; ?><?php endforeach ?>
        </style>
    <?php endif; ?>
    <!-- Load inline Css; -->
    <!-- Favicon-->
    <link rel="icon" href="<?= HTTP_ASSETS ?>favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- Custom Css -->
    <link href="<?= HTTP_ASSETS ?>css/style.css" rel="stylesheet">
    <link href="<?= HTTP_ASSETS ?>css/index.css" rel="stylesheet">
    <!-- Load external Js files header -->
    <?php if (isset($aExtJsHdr)) : ?>
        <?php foreach ($aExtJsHdr as $v) : ?>
            <script type="text/javascript" src="<?php echo $v; ?>"></script>
        <?php endforeach ?>
    <?php endif; ?>
    <!-- Load external Js files header; -->

    <!-- Load inline Js header -->
    <?php if (isset($aIntJsHdr)) : ?>
        <script type="text/javascript">
            <?php foreach ($aIntJsHdr as $v) : ?>
                <?php echo $v; ?>
            <?php endforeach ?>
        </script>
    <?php endif; ?>
    <!-- Load inline Js header; -->
</head>

<body style="margin-top: 10px;background-color: var( --siteBgColor);">
    <div class="container">
        <div class="card" style="padding: 10px 0px 0px 30px;margin-bottom:0px">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6">
                                <h2 style="font-size: 25px;color: black;margin-left: 10px;" class="d-inline-block">Invoice# <?= $aInvoiceData['invoice_no'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="row sm-center">
                            <div class="col sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <img style="width: <?= ($aInvoiceData['logo_size'] == 0? '40': $aInvoiceData['logo_size'])?>%;" class="img-logo" src="<?= HTTP_ASSETS ?>uploads/invoice-logo/<?= $aInvoiceData['invoice_logo'] ?>?v=<?php echo time();?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php if (($aInvoiceData['status'] == 1) || ($aInvoiceData['status'] == 2)) : ?>
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-primary waves-effect" id="paybtn">Pay Now</button>
                                            </div>
                                        <?php endif ?>
                                        <div class="col-sm-12">
                                            <a href="<?= site_url() ?>pdf/<?= $hash ?>" target="_blank" rel="noopener noreferrer">
                                                <button type="button" class="btn btn-primary waves-effect" id="download">Download PDF</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row sm-center">
                            <div class="col-sm-8">
                                <div class="col-sm-12 m-0">
                                    <h5>From:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= ucwords($aInvoiceData['admin_company']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= strtolower($aInvoiceData['admin_email']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= ucFirst($aInvoiceData['admin_addr1']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['admin_mobile'] ?></b></p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 sm-none">
                                <div class="col-sm-12 m-0">
                                    <h5>Invoice No:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['invoice_no'] ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <h5>Date:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['issue_date'] ?></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="row sm-center">
                            <div class="col-sm-8">
                                <div class="col-sm-12 m-0">
                                    <h5>To:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= ucwords($aInvoiceData['client_name']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= ucwords($aInvoiceData['client_company']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= strtolower($aInvoiceData['client_email']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= ucFirst($aInvoiceData['client_addr1']) ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['client_mobile'] ?></b></p>
                                </div>
                            </div>
                            <div class="col-sm-4 sm-none">
                                <div class="col-sm-12 m-0">
                                    <h5>Invoice Due:</h5>
                                </div>
                                <div class="col-sm-12">
                                    <p><b><?= $aInvoiceData['invoice_due_in'] ?></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="row d-block d-sm-none sm-center">
                            <div class="col-sm-6 col-md-6">
                                <div class="col-sm-12 m-0">
                                    <h5>Invoice No:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['invoice_no'] ?></b></p>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <h5>Date:</h5>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <p><b><?= $aInvoiceData['issue_date'] ?></b></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-12 m-0">
                                    <h5>Invoice Due:</h5>
                                </div>
                                <div class="col-sm-12">
                                    <p><b><?= $aInvoiceData['invoice_due_in'] ?></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="row partition sm-none" style="margin: 10px 5px 0px 5px !important;">
                            <div class="m-0 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8 col-lg-8 m-0">
                                        <h3 class="d-inline-block f-left heading" style="font-size:15px;font-weight: 200;"><b style="font-weight: 800 !important;">Description</b></h3>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 m-0">
                                        <h3 class="d-inline-block f-left heading" style="font-size:15px;font-weight: 200;"><b style="font-weight: 800 !important;">Quantity</b></h3>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 m-0">
                                        <h3 class="d-inline-block f-left heading" style="font-size:15px;font-weight: 200;"><b style="font-weight: 800 !important;">Amount</b></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($aInvoiceData['item_desciption'] as $k => $v) : ?>
                            <div class="row partition sm-none" style="margin: 10px 5px 0px 5px !important;">
                                <div class="m-0 col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-8 col-lg-8 m-0">
                                            <p><?= $v ?></p>
                                        </div>
                                        <div class="col-sm-2 col-lg-2 m-0">
                                            <p><?= $aInvoiceData['quantity'][$k] ?></p>
                                        </div>
                                        <div class="col-sm-2 col-lg-2 m-0">
                                            <p><?= number_format($aInvoiceData['amount'][$k], 2); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <?php foreach ($aInvoiceData['item_desciption'] as $k => $v) : ?>
                            <div class="row d-block d-sm-none" style="margin: 10px 5px 0px 5px !important;">
                                <div class="col-sm-12">
                                    <div class="row partition">
                                        <div class="col-sm-12 m-0">
                                            <h3 class="d-inline-block w-100 heading" style="font-size:15px;font-weight: 200;"><b style="font-weight: 800 !important;">Description</b>
                                            </h3>
                                                <span><?= $v ?></span>
                                        </div>
                                    </div>
                                    <div class="row partition">
                                        <div class="col-sm-12 m-0">
                                            <h3 class="d-inline-block w-100 heading" style="font-size:15px;font-weight: 200;display: inline !important;"><b style="font-weight: 800 !important;">Quantity</b>
                                            </h3>
                                                <span style="float: right;"><?= $aInvoiceData['quantity'][$k] ?></span>
                                        </div>
                                    </div>
                                    <div class="row partition">
                                        <div class="col-sm-12 m-0">
                                            <h3 class="d-inline-block w-100 heading" style="font-size:15px;font-weight: 200;display: inline !important;"><b style="font-weight: 800 !important;">Amount</b>
                                            </h3>
                                                <span style="float: right;"><?= $aInvoiceData['amount'][$k] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <div class="row">
                            <div class="col-sm-12 mb-0" style="margin-top: 30px;">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4"></div>
                                    <div class="col-sm-12 col-md-8 col-lg-8 mb-0">
                                        <div class="col-sm-12 partition">
                                            <div class="col-sm-12" style="margin-bottom: 2px;padding-right: 17px;">
                                                <div class="row">
                                                    <div class="col-sm-9 m-0">
                                                     Sub Total
                                                    </div>
                                                    <div class="col-sm-3 m-0">
                                                        <span><?= number_format($aInvoiceData['sub_total'], 2); ?></span></div>
                                                    </div>
                                                </div>
                                        </div>
                                        <?php if ($aInvoiceData['discount']) : ?>
                                            <div class="col-sm-12 partition">
                                                <div class="col-sm-12" style="margin-bottom: 2px;padding-right: 17px;"><?= $aInvoiceData['discount_name'] ?> <span style="float: right;"><?= $aInvoiceData['discount_amount'] ?></span></div>
                                            </div>
                                        <?php endif ?>
                                        <div class="col-sm-12 partition">
                                             <div class="row">
                                                    <div class="col-sm-9 m-0">
                                                   <b>Total (<span class="currencyUnit"><?= $aInvoiceData['currency'] ?><span></b>)
                                                    </div>
                                                    <div class="col-sm-3 m-0">
                                                        <span><?= number_format($aInvoiceData['total'], 2); ?></span>
                                                    </div>
                                                </div>
                                            <div class="col-sm-12" style="border: 3px solid var(--siteBgColor);padding: 8px 15px 6px 0px;">
                                             <div class="row">
                                                    <div class="col-sm-9 m-0">
                                                        <span style="padding: 10px 15px 10px 15px;background-color: var(--siteBgColor);color: white;">Balance</span>
                                                    </div>
                                                    <div class="col-sm-3 m-0">
                                                        <span><?= number_format($aInvoiceData['total'], 2); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mb-0" style="margin-top: 30px;">
                                <p><?= $aInvoiceData['footer'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="card" style="padding: 10px 0px 0px 10px;margin: 0px;background: white">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="body">
                            <p>Our PayPal Id: <?= $aInvoiceData['footer_email'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <form action="<?php echo SANDBOX ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr" ?>" method="post" class="d-none" id="paypal" target="_top">
        <input type='hidden' name='business' value=''> <input type='hidden' name='item_name' value=''> <input type='hidden' name='item_number' value=''> <input type='hidden' name='amount' value=''> <input type='hidden' name='no_shipping' value=''> <input type='hidden' name='currency_code' value=''> <input type='hidden' name='notify_url' value=''>
        <input type='hidden' name='cancel_return' value=''>
        <input type='hidden' name='return' value=''>
        <input type="hidden" name="cmd" value="_xclick">
    </form>
    <!-- Load external files -->
    <!-- Jquery Core Js -->
    <script src="<?= HTTP_ASSETS ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?= HTTP_ASSETS ?>plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Custom Js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <?php if (isset($aExtJs)) : ?>
        <?php foreach ($aExtJs as $v) : ?>
            <script type="text/javascript" src="<?php echo $v; ?>"></script>
        <?php endforeach ?>
    <?php endif; ?>
    <!-- Load external files; -->

    <!-- Load inline script -->
    <?php if (isset($aIntJs)) : ?>
        <script type="text/javascript">
            <?php foreach ($aIntJs as $v) : ?>
                <?php echo $v; ?>
            <?php endforeach ?>
        </script>
    <?php endif; ?>
    <!-- Load inline script; -->
</body>

</html>
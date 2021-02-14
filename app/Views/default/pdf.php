<!DOCTYPE html>
<html lang="en">
<?php
// print_r($aInvoiceData);
// die();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice- <?= $aInvoiceData['invoice_no'] ?></title>
    <link rel="stylesheet" href="<?php //echo BASE_URL . 'assets/pdf/fonts2/fonts.css'; 
                                    ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .hide {
            visibility: hidden
        }

        .pb-05 {
            padding-bottom: 0.5em;
        }

        .pl-1 {
            padding-left: 1em !important;
        }

        .m-0 {
            margin: 0;
        }

        .pb-1 {
            padding-bottom: 1em !important;
        }

        .p-0 {
            padding: 0;
        }

        .w-40 {
            width: 5%;
        }

        .w-50 {
            width: 50%;
        }

        hr {
            border: none;
            border-bottom: 1px solid #cccccc;
        }

        .inputTagline {
            font-size: 12px;
        }

        .tb-1 {
            width: 100%;
            /* border: 1px solid black; */
        }

        .display {
            display: inline;
        }

        .f-07 {
            font-size: 0.7em;
        }

        .f-09 {
            font-size: 0.9em;
        }

        .center {
            text-align: center;
        }

        .bg {
            background: gainsboro;
        }

        input[type="checkbox"]+label {
            display: inline-block;
            width: 18px;
            /* position: relative; */
        }

        .check[type="checkbox"]:checked+label {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
        }

        .check[type="checkbox"]:checked+label:after {
            content: "\f00c";
        }

        .input {
            border: none;
        }

        .bt {

            border-bottom: 1px solid #000;
        }

        .w-27 {
            width: 27%;
        }

        .fw {
            font-weight: bold;
        }

        .w-100 {
            width: 100%;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }

        .w-40 {
            width: 40%;
        }

        .border {
            border: 1px solid black
        }

        .f-1 {
            font-size: 1em;
        }

        .plr-08 {
            padding-left: 0.8em;
            padding-right: 0.8em;
        }

        .plr-1 {
            padding-left: 1em;
            padding-right: 1em;
        }

        .pr-2 {
            padding-right: 2em;
        }

        .pr-1 {
            padding-right: 1em;
        }

        .ptb-01 {
            padding-top: 0.1em;
            padding-bottom: 0.1em;
        }

        .f-065 {
            font-size: 0.65em;
        }

        ol {
            font-size: 15px;
            line-height: 1;
        }

        .list {
            margin: 0;
            position: relative;
            top: -13px;
            left: 14;
        }

        .unlist {
            margin: 0;
            position: relative;
            top: 4px;
            left: 14;
        }

        .list-none {
            list-style: none;
        }

        .mt-1 {
            margin-top: 1em !important;
        }

        .mt-3 {
            margin-top: 3em;
        }

        .b-collapse {
            border-collapse: collapse;
        }

        .margin {
            margin-top: 2.5em;
        }

        .cusmargin {
            margin-top: 2.05em;
        }

        .mt-4 {
            margin-top: 4em;
        }

        .pt-4 {
            padding-top: 4em;
        }

        .mt-205 {
            margin-top: 2.5em;
        }

        .mt-2 {
            margin-top: 2em !important;
        }

        .mt-08 {
            margin-top: 0.8em;
        }

        .justify {
            text-align: justify;
        }

        .page_break {
            page-break-before: always;
        }

        .bb {
            border-bottom: 1px solid black;
        }

        .br {
            border-right: 1px solid black;
        }

        .ml-5 {
            margin-left: 5em !important;
        }

        .ml-1 {
            margin-left: 1em;
        }

        .pl-2 {
            padding-left: 2em !important;
        }

        .pl-5 {
            padding-left: 5em !important;
        }

        .pr-05 {
            padding-right: 0.5em;
        }

        .mtb-1 {
            margin-top: 1em;
            margin-bottom: 1em;
        }

        .p-1 {
            padding: 1em;
        }

        .mt-05 {
            margin-top: 0.5em !important;
        }

        .pl-05 {
            padding-left: 0.5em;
        }

        .ml-05 {
            margin-left: 0.5em;
        }

        @page {
            margin: 30px;
        }

        .mt-0 {
            margin-top: 0em;
        }

        .pt-1 {
            padding-top: 1em;
        }

        .mb-0 {
            margin-bottom: 0em;
        }

        .mb-05 {
            margin-bottom: 0.5em;
        }

        .pb-05 {
            padding-bottom: 0.5em;
        }

        .mb-1 {
            margin-bottom: 0.5em;
        }

        .pt-05 {
            padding-top: 0.5em;
        }

        .ver-align {
            vertical-align: super;
        }

        /* .occ-check{
				font-size: 0.8em;
		    	width: 0px !important;
		    	padding-left: 0.5em;
		    	padding-right: 0.7em;
			} */
        /* .lh {
				line-height: 12px;
			} */
        .mt-2 {
            margin-top: 2em;
        }

        .pt-5 {
            padding-top: 5em;
        }

        .list-none {
            list-style: none;
        }

        .partition {
            border-bottom: 1px solid #9685854f;
        }

        /** Define the margins of your page **/
        @page {
            margin: 80px 10px;
        }

        header {
            position: fixed;
            left: 30px;
            right: 0px;
            top: 0px;
            height: 700px;
            margin-top: -70px;
        }

        .description p,
        .footer-note p {
            margin: 0 !important;
        }

        footer {
            position: fixed;
            left: 30px;
            right: 0px;
            bottom: 0px;
            height: 50px;
            margin-bottom: -50px;
        }

        #watermark {
            position: fixed;
            top: 35%;
            width: 100%;
            font-size: 125px;
            text-align: center;
            opacity: .1;
            transform: rotate(25deg);
            transform-origin: 50% 50%;
            z-index: -1000;
        }
        .heading {
    text-transform: uppercase;
    color: #499484 !important;
}
    </style>
</head>

<body class='m-0 pl-2 pr-2'>
    <div id="watermark">

        <?php
        switch ($aInvoiceData['status']) {
            case 0:
        ?>
                DRAFT
            <?php
                break;
            case 1:
            case 2:
            ?>
                UNPAID
            <?php
                break;
            case 3:
            ?>
                PAID
            <?php
                break;
            default:
            ?>
        <?php } ?>
    </div>

    <table class='tb-1 w-100'>
        <tr>
            <td colspan='6'>
                <img style="width: <?= ($aInvoiceData['logo_size'] == 0? '40': $aInvoiceData['logo_size'])?>%;" src="<?= HTTP_ASSETS ?>uploads/invoice-logo/<?= $aInvoiceData['invoice_logo'] ?>?v=<?php echo time();?>">
            </td>
        </tr>
        <tr>
            <td class="mt-2" colspan=5>
                <b>From:</b>
            </td>
            <td class="mt-2" colspan=1>
                <b>Invoice No:</b>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=5>
                <?= ucwords($aInvoiceData['admin_company']) ?>
                
            </td>
            <td colspan=1>
                <?= $aInvoiceData['invoice_no'] ?>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=5>
                <?= strtolower($aInvoiceData['admin_email']) ?>
            </td>
            <td colspan=1>
                <b>Date:</b>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=5>
                <?= ucFirst($aInvoiceData['admin_addr1']) ?>
            </td>
            <td colspan=1>
                <?= $aInvoiceData['issue_date'] ?>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=5>
                <?= $aInvoiceData['admin_mobile'] ?>
               <div style="width: 65%;margin-top: 15px;margin-bottom: 15px;border-bottom: 2px solid #9685854f;"></div>
            </td>
            <td colspan=1>
                <b>Invoice Due:</b>

            </td>
        </tr>
        <tr style="border-bottom:2px;">
            <td class="mt-05" colspan=5>
            </td>
            <td colspan=1>
                <?= $aInvoiceData['invoice_due_in'] ?>
            </td>
        </tr>
    //     <hr class="partition" style="
    // border: none;
    // height: 3px;
    // color: #333; 
    // background-color: #333; 
    // ">
        <tr>
            <td class="mt-2" colspan=4>
                <b>To:</b>
            </td>
            <td class="mt-2" colspan=2>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=4>
                <?= ucwords($aInvoiceData['client_name']) ?>
            </td>
            <td colspan=2>
            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=4>
                <?= ucwords($aInvoiceData['client_company']) ?>
            </td>
            <td colspan=2>

            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=4>
                <?= strtolower($aInvoiceData['client_email']) ?>
            </td>
            <td colspan=2>

            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=4>
                <?= ucFirst($aInvoiceData['client_addr1']) ?>
            </td>
            <td colspan=2>

            </td>
        </tr>
        <tr>
            <td class="mt-05" colspan=3>
                <?= $aInvoiceData['client_mobile'] ?>
            </td>
            <td colspan=3>

            </td>
        </tr>
        <tr>
            <td class="mt-2 partition pb-1 heading" colspan=4>
                Description
            </td>
            <td class="mt-2 partition pb-1 heading" colspan=1>
                Quantity
            </td>
            <td class="mt-2 partition pb-1 heading" colspan=1>
                Amount
            </td>
        </tr>
        <?php foreach ($aInvoiceData['item_desciption'] as $k => $v) : ?>
            <tr>
                <td class="partition pb-05 pr-2 description" style="padding-left: 1em;" colspan=4>
                    <span><?= $v ?></span>
                </td>
                <td class="partition pb-05" colspan=1>
                    <?= $aInvoiceData['quantity'][$k] ?>
                </td>
                <td class=" partition pb-05" colspan=1>
                    <?= $aInvoiceData['amount'][$k] ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td class="pb-05" style="margin-top: 4em !important;" colspan=2></td>
            <td class="partition pb-05" style="margin-top: 4em !important; padding-left: 2em;" colspan=3>
                SubTotal
            </td>
            <td class="partition pb-05" style="margin-top: 4em !important; float: right !important;" colspan=1>
                <?= number_format($aInvoiceData['sub_total'], 2); ?>
            </td>
        </tr>
        <?php if ($aInvoiceData['discount']) : ?>
            <tr>
                <td class="pb-05" style="margin-top: 0.5em !important;" colspan=2></td>
                <td class="partition pb-05" style="margin-top: 0.5em !important; padding-left: 2em;" colspan=3>
                    <?= $aInvoiceData['discount_name'] ?>
                </td>
                <td class="partition pb-05" style="margin-top: 0.5em !important; float: right !important;" colspan=1>
                    <?= $aInvoiceData['discount_amount'] ?>
                </td>
            </tr>
        <?php endif ?>
        <tr>
            <td class="pb-05" style="margin-top: 0.5em !important;" colspan=2></td>
            <td class="partition pb-05" style="margin-top: 0.5em !important; padding-left: 2em;" colspan=3>
                Total (<?= $aInvoiceData['currency'] ?>)
            </td>
            <td class="partition pb-05" style="margin-top: 0.5em !important; float: right !important;" colspan=1>
                <?= number_format($aInvoiceData['total'], 2); ?>
            </td>
        </tr>
        <tr>
            <td class="pb-05" colspan=2></td>
            <td class=" pb-05" style="padding-left: 2em;" colspan=3>
                <span>Balance</span>
                <span class="currencyUnit"> <?= $aInvoiceData['currency'] ?></span>
            </td>
            <td class=" pb-05" style="float: right !important;" colspan=1>
                <?= number_format($aInvoiceData['total'], 2); ?>
            </td>
        </tr>
        <tr>
            <td class="footer-note" colspan='12'>
                <?= $aInvoiceData['footer'] ?>
            </td>
        </tr>
    </table>
    <footer>
        Our PayPal Id: <?= $aInvoiceData['footer_email'] ?>
    </footer>
</body>

</html>
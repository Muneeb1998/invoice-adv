<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo HTTP_ASSETS; ?>css/ionicons.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo HTTP_ASSETS; ?>css/font-awesome.min.css"> -->
    <title><?php echo 'Login - ' . APP_NAME; ?></title>
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
    <link href="<?= HTTP_ASSETS ?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?= HTTP_ASSETS ?>plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?= HTTP_ASSETS ?>plugins/animate-css/animate.css" rel="stylesheet" />
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

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">
                <img src="<?php echo HTTP_ASSETS; ?>images/logo.png" alt="homepage" class="dark-logo" style="width: 80%;"/>
            </a>
        </div>
        <div class="card">
            <div class="body">
                <form id="login" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button class="btn btn-block waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Load external files -->
    <!-- Jquery Core Js -->
    <script src="<?= HTTP_ASSETS?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?= HTTP_ASSETS?>plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?= HTTP_ASSETS?>plugins/node-waves/waves.js"></script>
    <!-- Validation Plugin Js -->
    <script src="<?= HTTP_ASSETS?>plugins/jquery-validation/jquery.validate.js"></script>
    <!-- Custom Js -->
    <script src="<?= HTTP_ASSETS?>js/admin.js"></script>
    <script src="<?= HTTP_ASSETS?>js/pages/examples/sign-in.js"></script>
	<script src="<?= HTTP_ASSETS ?>js/script.js"></script>
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
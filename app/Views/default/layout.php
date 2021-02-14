<!DOCTYPE html>
<html>

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?php echo base_url('assets/imgs/icon.png'); ?>">
	<!-- external font -->
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
	<title><?php echo $metaTitle; ?></title>
	<!-- Load external Css files -->
	<?php if (isset($aExtCss)) : ?>
		<?php foreach ($aExtCss as $v) : ?>
			<link rel="stylesheet" type="text/css" href="<?php echo $v; ?>">
		<?php endforeach ?>
	<?php endif; ?>
	<!-- Load external Css files; -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Favicon-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<!-- Bootstrap Core Css -->
	<link href="<?= HTTP_ASSETS ?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
	<!-- Waves Effect Css -->
	<link href="<?= HTTP_ASSETS ?>plugins/node-waves/waves.css" rel="stylesheet" />
	<!-- Animation Css -->
	<link href="<?= HTTP_ASSETS ?>plugins/animate-css/animate.css" rel="stylesheet" />
	<!-- Morris Chart Css-->
	<link href="<?= HTTP_ASSETS ?>plugins/morrisjs/morris.css" rel="stylesheet" />
	<!-- Custom Css -->
	<link href="<?= HTTP_ASSETS ?>css/style.css" rel="stylesheet">
	<link href="<?= HTTP_ASSETS ?>css/index.css?v=<?php echo time();?>" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="<?= HTTP_ASSETS ?>css/themes/all-themes.css" rel="stylesheet" />

	<!-- Load inline Css -->
	<?php if (isset($aIntCss)) : ?>
		<style type="text/css">
			<?php foreach ($aIntCss as $v) : ?><?php echo $v; ?><?php endforeach ?>
		</style>
	<?php endif; ?>
	<!-- Load inline Css; -->

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

<body class="theme-red">
	<!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-red">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
			<p>Please wait...</p>
		</div>
	</div>
	<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
		<?php $this->renderSection('content') ?>
	</div>
	</div>
	<!-- Jquery Core Js -->
	<script src="<?= HTTP_ASSETS ?>plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap Core Js -->
	<script src="<?= HTTP_ASSETS ?>plugins/bootstrap/js/bootstrap.js"></script>
	<!-- Select Plugin Js -->
	<!-- <script src="<?= HTTP_ASSETS ?>plugins/bootstrap-select/js/bootstrap-select.js"></script> -->
	<!-- Slimscroll Plugin Js -->
	<script src="<?= HTTP_ASSETS ?>plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
	<!-- Waves Effect Plugin Js -->
	<script src="<?= HTTP_ASSETS ?>plugins/node-waves/waves.js"></script>
	<!-- Jquery CountTo Plugin Js -->
	<script src="<?= HTTP_ASSETS ?>plugins/jquery-countto/jquery.countTo.js"></script>
	<!-- Custom Js -->
	<script src="<?= HTTP_ASSETS ?>js/admin.js"></script>
	<script src="<?= HTTP_ASSETS ?>js/pages/index.js"></script>
	<!-- Demo Js -->
	<script src="<?= HTTP_ASSETS ?>js/demo.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
	<script src="<?= HTTP_ASSETS ?>js/script.js"></script>
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
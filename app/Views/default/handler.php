<?php $this->extend($_t.'layout'); ?>
<?php $this->section('content'); ?>
<?php echo (!@$noHeader ? $this->include($_t.'sections/header') : ''); ?>
<?php echo (!@$noLeftSidebar ? $this->include($_t.'sections/left_sidebar') : ''); ?>
<?php echo $this->include($_t.$_p); ?>
<?php $this->endSection(); ?>
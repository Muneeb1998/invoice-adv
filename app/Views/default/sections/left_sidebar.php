<nav class="navbar">
  <div class="container-fluid">
    <div class="navbar-header">
      <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
      <a href="javascript:void(0);" class="bars"></a>
      <a class="navbar-brand" href="<?= site_url() ?>"> <img src="<?php echo HTTP_ASSETS; ?>images/logo.png" alt="homepage" class="dark-logo" /></a>
    </div>
  </div>
</nav>
<section>
  <!-- Left Sidebar -->
  <aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
      <div class="info-container">
        <h3><?= $role == 'sa' ? 'Super Admin' : 'Admin' ?></h3>
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $name ?></div>
        <div class="email"><?= $email ?></div>
        <div class="btn-group user-helper-dropdown">
        </div>
      </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
      <ul class="list">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
          <a href="<?= site_url() ?>">
            <i class="material-icons">home</i>
            <span>Dashboard</span>
          </a>
        </li>
        <?php if ($role == 'sa') : ?>
          <li>
            <a href="javascript:void(0);" class="menu-toggle">
              <i class="material-icons">person</i>
              <span>Admin</span>
            </a>
            <ul class="ml-menu">
              <li>
                <a href="<?= site_url('admin') ?>">Admin</a>
              </li>
              <li>
                <a href="<?= site_url('admin/add') ?>">Add</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="<?= site_url('ChangePassword') ?>">
              <i class="material-icons">lock</i>
              <span>Change Password</span>
            </a>
          </li>
        <?php endif ?>
        <?php if ($role == 'a') : ?>
          <li>
          <a href="<?= site_url('client') ?>">
            <i class="material-icons">person</i>
            <span>Client</span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('client/add') ?>">
            <i class="material-icons">add</i>
            <span>Add Client</span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('invoice') ?>">
            <i class="material-icons">sticky_note_2</i>
            <span>Invoice</span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('invoice/add') ?>">
            <i class="material-icons">add</i>
            <span>Add Invoice</span>
          </a>
        </li>
          <!-- <li>
            <a href="<?= site_url('client') ?>" class="menu-toggle">
              <i class="material-icons">person</i>
              <span>Client</span>
            </a>
            <ul class="ml-menu">
              <li>
                <a href="<?= site_url('client') ?>">Client</a>
              </li>
              <li>
                <a href="<?= site_url('client/add') ?>">Create</a>
              </li>
            </ul>
          </li> -->
          <!-- <li>
            <a href="<?= site_url('invoice') ?>" class="menu-toggle">
              <i class="material-icons">sticky_note_2</i>
              <span>Invoice</span>
            </a>
            <ul class="ml-menu">
              <li>
                <a href="<?= site_url('invoice') ?>">Invoice</a>
              </li>
              <li>
                <a href="<?= site_url('invoice/add') ?>">Create</a>
              </li>
            </ul>
          </li> -->
          <!--  -->
          <li>
            <a href="javascript:void(0);" class="menu-toggle">
              <i class="material-icons">settings</i>
              <span>Settings</span>
            </a>
            <ul class="ml-menu">
              <li>
                <a href="<?= site_url('settings') ?>">Settings</a>
              </li>
              <li>
                <a href="<?= site_url('SmtpSettings') ?>">Email Settings</a>
              </li>
              <li>
                <a href="<?= site_url('ChangePassword') ?>">Change Password</a>
              </li>
            </ul>
          </li>
          <!--  -->
        <?php endif ?>
        <li>
          <a href="<?= site_url('logout') ?>">
            <i class="material-icons">input</i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- #Menu -->
  </aside>
  <!-- #END# Left Sidebar -->
</section>
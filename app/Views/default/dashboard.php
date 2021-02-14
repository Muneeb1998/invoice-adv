<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6">
                                <h2 class="d-inline-block">DASHBOARD</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <!-- Widgets -->
                        <?php //if ($role == 'sa') : 
                        ?>
                        <div class="row clearfix count">
                            <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                <div style="border: 1px solid;padding: 10px;max-height: 400px;min-height: 400px;">
                                    <h4 class="m-0" style="color: var(--siteColor);margin-bottom: 10px !important;">Recent Activites</h4>
                                    <div id="activites"></div>
                                    <div>
                                        <button type="button" data="activites" class="btn btn-primary waves-effect more-act" style="width: 100%;">
                                            More Recent Activites
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                <div style="border: 1px solid;padding: 10px;max-height: 400px;min-height: 400px;">
                                    <h4 class="m-0" style="color: var(--siteColor);margin-bottom: 10px !important;">Recent Paid</h4>
                                    <div id="recent-paid"></div>
                                    <div>
                                        <button data="paid" class="btn btn-primary waves-effect more-act" style="width: 100%;">
                                            More Paid Activites
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
                                    <div class="info-box hover-expand-effect m-0">
                                        <div class="icon">
                                            <i class="material-icons">sticky_note_2</i>
                                        </div>
                                        <div class="content">
                                            <div class="text m-0">Total Collected this Year</div>
                                            <div class="number count-to total-invoice m-0" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
                                    <div class="info-box hover-expand-effect m-0">
                                        <div class="icon">
                                            <i class="material-icons">playlist_add_check</i>
                                        </div>
                                        <div class="content">
                                            <div class="text m-0">TOTAL OUTSTANDING</div>
                                            <div class="number count-to paid-invoice m-0" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
                                    <div class="info-box hover-expand-effect m-0">
                                        <div class="icon">
                                            <i class="material-icons">help</i>
                                        </div>
                                        <div class="content">
                                            <div class="text m-0">Total Overdue</div>
                                            <div class="number count-to unpaid-invoice m-0" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo site_url('client') ?>">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
                                        <div class="info-box hover-expand-effect m-0">
                                            <div class="icon">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="content">
                                                <div class="text m-0">TOTAL CLIENT</div>
                                                <div class="number count-to total-client m-0" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="<?php echo site_url('invoice') ?>">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
                                        <div class="info-box hover-expand-effect m-0">
                                            <div class="icon">
                                                <i class="material-icons">playlist_add_check</i>
                                            </div>
                                            <div class="content">
                                                <div class="text m-0">PAID INVOICE</div>
                                                <div class="number count-to total-paid m-0" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php //endif 
                        ?>
                        <!-- #END# Widgets -->
                    </div>
                </div>
            </div>
        </div>
        <!-- graph -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2 class="d-inline-block">Paid/Unpaid Invoice States</h2>
                    <ul class="header-dropdown m-r--5 d-none">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2 class="d-inline-block">Invoice States</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <!-- Widgets -->
                            <?php //if ($role == 'sa') : 
                            ?>
                            <div class="row clearfix">
                                <div class="col-sm-12" style="font-size: 15px;text-transform: capitalize;font-weight: 600;">
                                    <div class="row">
                                        <div class="col-xs-3 col-sm-3 status">
                                            <span id="draft"></span>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 status">
                                            <span id="sent"></span>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 status">
                                            <span id="paid"></span>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 status">
                                            <span id="unpaid"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="donut_chart" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <?php //endif 
                            ?>
                            <!-- #END# Widgets -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px 0px 15px;">
                <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
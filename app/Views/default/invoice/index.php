<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6">
                                <h2>Invoice</h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="<?= site_url('invoice/add') ?>">
                                    <button class="btn btn-primary m-t-15 waves-effect" style="float: right;margin-right: 12px;">
                                        Add Invoice
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="tab-content">
                            <div class="tab-pane show active p-4 cpt-0">
                                <div class="row">
                                    <div class="col-sm-12 m-0" style="margin-bottom: 15px !important;">
                                        <div class="col s8 right no-p" id="ct">
                                            <ul class="status m-0">
                                                <li data-status='2' class="active">UnPaid</li>
                                                <li data-status='3'>Paid</li>
                                                <li data-status='0'>Draft</li>
                                                <li data-status='11'>Archieve</li>
                                                <li data-status='all'>All</li>
                                                <li data-status='1' class="d-none">Sent</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="invoice">
                                                <thead>
                                                    <tr>
                                                        <th class="th">
                                                            <input type="checkbox" id="basic_checkbox_2" class="filled-in check-all" />
                                                            <label for="basic_checkbox_2"></label>
                                                        </th>
                                                        <th class="th">Client Name</th>
                                                        <th class="th">Company</th>
                                                        <th class="th">Invoice #</th>
                                                        <th class="th">Total</th>
                                                        <th class="th">Issue Date</th>
                                                        <th class="th">Due Date</th>
                                                        <th class="th">Status</th>
                                                        <th class="th"><span>Action</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="t-body">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- view -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewModalLabel">View Invoice</h4>
            </div>
            <div class="view-body table-responsive">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!--  -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="updateModalLabel">Edit Client</h4>
            </div>
            <div class="update-body">
                <?php echo $form ?>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-link waves-effect">SAVE CHANGES</button> -->
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="height: 100vh;">
            <iframe id="invoiceView" src="" frameborder="0" width="100%" height="100%"></iframe>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
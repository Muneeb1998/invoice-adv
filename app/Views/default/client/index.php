<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-6 col-sm-6">
                                <h2>Client(s)</h2>
                            </div>
                            <div class="col-xs-6">
                                <a href="<?= site_url('client/add') ?>">
                                    <button class="btn btn-primary m-t-15 waves-effect" style="float: right;margin-right: 12px;">
                                        Add Client
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="tab-content">
                            <div class="tab-pane show active p-4 cpt-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="admin">
                                        <thead>
                                            <tr>
                                                <th class="th">
                                                    <input type="checkbox" id="basic_checkbox_2" class="filled-in check-all" />
                                                    <label for="basic_checkbox_2"></label>
                                                </th>
                                                <th class="th">Name</th>
                                                <th class="th">Company</th>
                                                <th class="th">Email</th>
                                                <th class="th">Country</th>
                                                <th class="th">Mobile Number</th>
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
</section>
<!-- view -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewModalLabel">View Client</h4>
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
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-6">
                                <h2>Admin(s)</h2>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="tab-content">
                            <div class="tab-pane show active p-4 cpt-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered email" id="admin">
                                        <thead>
                                            <tr>
                                                <th class="th">S.no</th>
                                                <th class="th">Name</th>
                                                <th class="th">Email</th>
                                                <th class="th">Role</th>
                                                <th class="th">Password</th>
                                                <th class="th">Action</th>
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
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="updateModalLabel">Edit Admin</h4>
            </div>
            <div class="update-body">
               <div class="clearfix">
                    <div class="col-sm-12">
                         <?php echo $form ?>
                    </div>
               </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-link waves-effect">SAVE CHANGES</button> -->
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-sm-8">
                                <div class="icon site-icon">
                                    <i class="material-icons">person_add</i>
                                </div>
                                <h2 class="d-inline-block">Add Invoice</h2>
                            </div>
                            <div class="col-sm-4">
                                <button data-status="0" onclick="$('#invoice').submit()" style="margin-right: 10px;padding: 6px 25px;" class="btn btn-primary action m-t-15 waves-effect">
                                    <div class="preloader pl-size-xs d-none">
                                        <div class="spinner-layer pl-red-grey">
                                            <div class="circle-clipper left">
                                                <div class="circle"></div>
                                            </div>
                                            <div class="circle-clipper right">
                                                <div class="circle"></div>
                                            </div>
                                        </div>
                                    </div>Save As Draft
                                </button>
                                <button onclick="$('#invoice').submit()" data-status="1" class="btn btn-primary m-t-15 waves-effect action">
                                    <div class="preloader pl-size-xs d-none">
                                        <div class="spinner-layer pl-red-grey">
                                            <div class="circle-clipper left">
                                                <div class="circle"></div>
                                            </div>
                                            <div class="circle-clipper right">
                                                <div class="circle"></div>
                                            </div>
                                        </div>
                                    </div>Save and Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div id="real_time_chart" class="dashboard-flot-chart">
                            <?php echo $form ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="sentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewModalLabel"><?= ucwords(APP_NAME) ?></h4>
            </div>
            <div class="mail-body" style="padding: 20px;">
                <div class="row">
                    <div class="col-sm-12 partition" style="margin-top: 5px;padding-bottom: 12px;">
                        <div class="row">
                            <div class="col-sm-12" style="padding-left: 20px;"><b>To</b></div>
                            <div class="col-sm-12">
                                <div class="form-line">
                                    <input type="text" readonly name="mailto" class="form-control form-control" readonly="readonly" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 partition" style="margin-top: 5px;padding-bottom: 12px;">
                        <div class="row">
                            <div class="col-sm-12" style="padding-left: 20px;"><b>BCC</b></div>
                            <div class="col-sm-12">
                                <div class="form-line">
                                    <input type="text" name="mailbcc" class="form-control form-control" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 partition" style="margin-top: 5px;padding-bottom: 12px;">
                        <div class="row">
                            <div class="col-sm-12" style="padding-left: 20px;"><b>Subject</b></div>
                            <div class="col-sm-12">
                                <div class="form-line">
                                    <input type="text" name="mailsubject" class="form-control form-control" readonly="readonly" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 partition" style="margin-top: 5px;padding-bottom: 12px;">
                        <div class="row">
                            <div class="col-sm-12" style="padding-left: 20px;"><b>Message</b></div>
                            <div class="col-sm-12">
                                <div class="form-line">
                                    <textarea name="" id="msg" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-link waves-effect" id="sendInvoiveData">
                    <div class="preloader pl-size-xs d-none">
                        <div class="spinner-layer pl-red-grey">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                            <div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
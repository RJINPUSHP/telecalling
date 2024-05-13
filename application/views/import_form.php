<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Import Customer <br>
            <small>Download Sample File</small>
            <a class="btn btn-sm btn-danger" href="<?php echo base_url() ?>db/user.csv" title="Sample csv">
            <i class="fa fa-file"></i>
            </a>

        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" action="<?php echo base_url() ?>import/do_import" method="post" role="form"
                        enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="taskTitle">File</label>
                                        <input type="file" class="form-control" required name="csv_file"
                                            accept=".csv" />
                                    </div>

                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
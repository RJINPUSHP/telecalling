<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Customer Management
            <small>Add / Edit Customer</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Customer Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addBooking" action="<?php echo base_url() ?>booking/addNewBooking"
                        method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control required"
                                            value="<?php echo set_value('name'); ?>" placeholder="Name" id="name"
                                            name="name" maxlength="256" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Mobile</label>
                                        <input type="number" class="form-control required"
                                            value="<?php echo set_value('mobile'); ?>" id="mobile" placeholder="Mobile"
                                            name="mobile" maxlength="256" />
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Village</label>
                                        <input type="text" class="form-control required"
                                            value="<?php echo set_value('village'); ?>" id="village"
                                            placeholder="Village" name="village" maxlength="256" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Father Name</label>
                                        <input type="text" class="form-control required"
                                            value="<?php echo set_value('fathername'); ?>" id="fathername" placeholder="Father Name"
                                            name="fathername" maxlength="256" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">State</label>
                                        <input type="text" class="form-control required"
                                            value="<?php echo set_value('state'); ?>" id="state" placeholder="State"
                                            name="state" maxlength="256" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">City</label>
                                        <input type="text" class="form-control required"
                                            value="<?php echo set_value('city'); ?>" id="city" placeholder="City"
                                            name="city" maxlength="256" />
                                    </div>

                                </div>
                            </div>

                          <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Gender</label>
                                        <select class="form-control required"
                                            value="<?php echo set_value('gender'); ?>" id="gender" placeholder="gender"
                                            name="gender" >
                                            <option>Select</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                            </select>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Pincode</label>
                                        <input type="number" class="form-control required"
                                            value="<?php echo set_value('pincode'); ?>" id="pincode" placeholder="pincode"
                                            name="pincode" maxlength="256" />
                                    </div>

                                </div>
                            </div>
                              <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Date Of Birth</label>
                                        <input type="date" class="form-control required"
                                            value="<?php echo set_value('dob'); ?>" id="dob"
                                            name="dob" /> 
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
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
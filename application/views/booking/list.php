 
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Customer Management
            <small>Add, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                <a class="btn btn-primary" href="<?php echo base_url(); ?>import"><i class="fa fa-plus"></i>
                        Import Customer</a>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>booking/add"><i class="fa fa-plus"></i>
                        Add Customer</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <div class="box-tools">
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="example1">
                             <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Name</th>
                                      <th>Father Name</th>
                                    <th>Mobile</th>
                                    <th>DOB</th>
                                    <th>Gender</th>
                                    
                                    <th>Permanent</th>
                                  
                                    <!--<th>State</th>-->
                                    
                                    <!--<th>City</th>-->
                                    <th>Created On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                
                        </table>

                    </div><!-- /.box-body -->
                   
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>



 

<script type="text/javascript" language="javascript">
$(document).ready(function(){

	function load_data(start, length)
	{
	    base_url='<?php echo base_url() ?>';
		var dataTable = $('#example1').DataTable({
		   
       dom: 'Bfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			"processing" : true,
			"serverSide" : true,"lengthChange": true,
			"order" : [],
			"retrieve": true, 
			"ajax" : {
				url:base_url+"booking/list",
				method:"POST",
				data:{start:start, length:length}
			}
		});
	}

	load_data();
 
	                       
});	
</script>

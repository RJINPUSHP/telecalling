<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>Response Feedback

        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="box-tools">
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Name</th>
                                    <th>Father Name</th>

                                    <th>Mobile</th>
                                    <th>DOB</th>
                                    <th>Gender</th>

                                    <th>Permanent</th>
                                    <th>Call Response</th>
                                    <th>Call Status</th>

                                    <th>Caller Name</th>
                                    <th>Call Date</th>
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
    $(document).ready(function () {

        function load_data(start, length) {
            base_url = '<?php echo base_url() ?>';
            var dataTable = $('#example1').DataTable({

                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                "processing": true,
                "serverSide": true, "lengthChange": true,
                "order": [],
                "retrieve": true,
                "ajax": {
                    url: base_url + "task/list",
                    method: "POST",
                    data: { start: start, length: length }
                }
            });
        }

        load_data();


    });	
</script>
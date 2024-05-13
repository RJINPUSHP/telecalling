<link href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
<link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
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
                        <table id="example" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>S NO</th>
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
                            <tbody>
                                <?php
                                if (!empty($records)) { 
                                    error_reporting(0);
                                    $i=1;
                                    foreach ($records as $record) {
                                        $callerId = $record->callerId;
                                        $q = $this->db->query("SELECT * FROM `tbl_booking` WHERE `bookingId`='$callerId' ");
                                        $row = $q->row();
                                       
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $record->name ?></td>
                                                <td><?php echo $row->fathername ?></td>
                                                <td><?php echo $record->mobile ?></td>
                                                <td><?php echo $row->dob ?> </td>
                                                <td><?php echo $row->gender ?> </td>
                                                <td><?php echo $row->village ?> </td>
                                                <td><?php echo $record->callres ?></td>
                                                <td><?php echo $record->callstatus ?></td>
                                                <td> <?php
                                                $createdBy = $record->createdBy;
                                                $q = $this->db->query("SELECT * FROM `tbl_booking` WHERE `bookingId`='$createdBy' ");
                                                $row = $q->row();

                                                if (!empty($row)) {
                                                    echo $row->name;
                                                }
                                                ?>
                                                </td>

                                               
                                                <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                            </tr>
                                        <?php                                     }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->
                     
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js" ></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js" ></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" ></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js" ></script>


 <script>
 
 $(document).ready(function() {
  $('#example').DataTable({
    searching: true,
    ordering: true,
    select: true,
    dom: 'fBrtip<"clear">l',
    "columnDefs": [{
      className: "dt-right",
      "targets": [0] // First column
    }],
   buttons: [
        'copy', 'excel', 'pdf'
    ]
  });
  // The data tables bootstrap css didn't include styling for the plugin buttons
  $('.dt-button').addClass('btn btn-default');
});

 
</script>
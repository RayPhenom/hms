<?php 
include('header.php');
include('includes/connection.php');

if (!isset($_GET['ticket_id'])) {
    header('location:generate_ticket.php');
}

$ticket_id = $_GET['ticket_id'];

$get_ticket_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_number, tbl_patient.first_name, tbl_patient.last_name, tbl_ticket.created_at 
                            FROM tbl_ticket 
                            JOIN tbl_patient ON tbl_ticket.patient_id = tbl_patient.id
                            WHERE tbl_ticket.id = '$ticket_id'");
$row = mysqli_fetch_array($get_ticket_query);
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-6 col-6">
                <h4 class="page-title">Ticket <?php echo $row['ticket_number']; ?></h4>
            </div>
            <div class="col-sm-6 col-6 text-right m-b-20">
                <a href="#" onclick="window.print()" class="btn btn-primary btn-rounded float-right"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <tbody>
                            <tr>
                                <td>Ticket Number:</td>
                                <td><?php echo $row['ticket_number']; ?></td>
                            </tr>
                            <tr>
                                <td>Patient Name:</td>
                                <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>

<?php
include('footer.php');
?>
<!-- Print ticket modal -->
<div id="print-ticket-modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Ticket</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 m-b-30">
                        <div class="invoice-address">
                            <h6>Hospital Address</h6>
                            <p>501, Silverside Road, Wilmington, Delaware, USA</p>
                        </div>
                    </div>
                    <div class="col-md-6 m-b-30">
                        <div class="invoice-address">
                            <h6>Patient Address</h6>
                            <p><?php echo $row['address']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 m-b-20">
                        <h6>Ticket Number</h6>
                        <h2><?php echo $row['ticket_number']; ?></h2>
                    </div>
                    <div class="col-sm-6 m-b-20">
                        <h6>Patient Name</h6>
                        <h2><?php echo $row['first_name']." ".$row['last_name']; ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice-address">
                            <h6>Ticket Time</h6>
                            <p><?php echo date('d M Y H:i:s', strtotime($row['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary print-btn" onclick="printDiv('print-ticket')">Print</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // function to print the ticket
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
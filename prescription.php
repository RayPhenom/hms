<?php
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
    exit;
}

include('header.php');
include('includes/connection.php');

// Retrieve the ticket details
$ticket_number = $_GET['ticket_number'];
$ticket_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_id,tbl_ticket.ticket_number, tbl_patient.first_name, tbl_patient.last_name, tbl_prescription.prescription, tbl_ticket.patient_id
    FROM tbl_ticket
    JOIN tbl_patient ON tbl_ticket.patient_id = tbl_patient.id
    LEFT JOIN tbl_prescription ON tbl_ticket.patient_id = tbl_prescription.patient_id
    WHERE tbl_ticket.ticket_number = '$ticket_number'");

$row = mysqli_fetch_array($ticket_query);

if (!$row) {
    echo '<div class="alert alert-danger">Invalid ticket number. Please try again.</div>';
    echo '<script type="text/javascript">swal("Invalid ticket number. Please try again.");</script>';
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_number = $_POST['ticket_number'];
    $ticket_id = $row['ticket_id'];
    $prescription = $_POST['prescription'];
    $doctor_id = $_SESSION['id'];
    $patient_id = $row['patient_id'];

    // Insert the prescription into the database
    $insert_prescription_query = mysqli_query($connection, "INSERT INTO tbl_prescription (ticket_id, patient_id, doctor_id, prescription) VALUES ('$ticket_id','$patient_id', '$doctor_id', '$prescription')");

    if ($insert_prescription_query) {
        echo '<div class="alert alert-success">Ticket updated successfully!</div>';
        echo '<script type="text/javascript">swal("Ticket updated successfully!");</script>';
    } else {
        echo '<div class="alert alert-danger">Error updating ticket. Please try again.</div>';
        echo '<script type="text/javascript">swal("Error updating ticket. Please try again.");</script>';
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4">
                <h4 class="page-title">Prescription </h4>
            </div>
            <div class="col-sm-8 text-right m-b-20">
                <a href="consultation.php" class="btn btn-primary btn-rounded float-right">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form method="POST">
                    <div class="form-group">
                        <label for="ticket_number">Ticket Number:</label>
                        <input class="form-control" type="text" id="ticket_number" name="ticket_number"
                            value="<?php echo $row['ticket_number']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="patient_name">Patient Name:</label>
                        <input class="form-control" type="text" id="patient_name" name="patient_name"
                            value="<?php echo $row['first_name'] . " " . $row['last_name']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="doctor_name">Doctor's Name</label>
                        <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?php echo $_SESSION['name']; ?>" readonly>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="patient_id">Patient ID:</label>
                        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $row['patient_id']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="prescription">Prescription:</label>
                        <textarea class="form-control" id="prescription" name="prescription" required></textarea>
                    </div>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary submit-btn">Save Prescription</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script type="text/javascript">
    <?php
    if (isset($msg)) {
        echo 'swal("' . $msg . '");';
    }
    ?>
</script>

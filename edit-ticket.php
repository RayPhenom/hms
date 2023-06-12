<?php
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
    exit;
}

include('header.php');
include('includes/connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_number = $_POST['ticket_number'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    // Update the ticket status to the selected value
    $update_ticket_query = mysqli_query($connection, "UPDATE tbl_ticket SET status = '$status' WHERE ticket_number = '$ticket_number'");

    // Check if the status is closed, then update the location to 'Closed'
    if ($status == 0) {
        $update_location_query = mysqli_query($connection, "UPDATE tbl_ticket SET current_location = 'closed' WHERE ticket_number = '$ticket_number'");
    } else {
        $update_location_query = mysqli_query($connection, "UPDATE tbl_ticket SET current_location = '$location' WHERE ticket_number = '$ticket_number'");
    }

    if ($update_ticket_query && $update_location_query) {
        echo '<div class="alert alert-success">Ticket updated successfully!</div>';
        echo '<script type="text/javascript">swal("Ticket updated successfully!");</script>';
    } else {
        echo '<div class="alert alert-danger">Error updating ticket. Please try again.</div>';
        echo '<script type="text/javascript">swal("Error updating ticket. Please try again.");</script>';
    }
}

// Retrieve the ticket details
$ticket_number = $_GET['ticket_number'];
$ticket_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_number, tbl_patient.first_name, tbl_patient.last_name, tbl_ticket.status, tbl_ticket.current_location
    FROM tbl_ticket
    JOIN tbl_patient ON tbl_ticket.patient_id = tbl_patient.id
    WHERE tbl_ticket.ticket_number = '$ticket_number'");

$row = mysqli_fetch_array($ticket_query);

if (!$row) {
    echo '<div class="alert alert-danger">Invalid ticket number. Please try again.</div>';
    echo '<script type="text/javascript">swal("Invalid ticket number. Please try again.");</script>';
    exit;
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4">
                <h4 class="page-title">Update Ticket Status</h4>
            </div>
            <div class="col-sm-8 text-right m-b-20">
                <a href="queue.php" class="btn btn-primary btn-rounded float-right">Back</a>
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
                        <label class="display-block">Ticket Location:</label>
                        <select class="form-control" name="location">
                            <option value="Waiting_Area" <?php if ($row['current_location'] == 'Waiting_Area') echo 'selected'; ?>>Waiting Area</option>
                            <option value="Triage" <?php if ($row['current_location'] == 'Triage') echo 'selected'; ?>>Triage</option>
                            <option value="Clinics" <?php if ($row['current_location'] == 'Clinics') echo 'selected'; ?>>Clinics</option>
                            <option value="Labs" <?php if ($row['current_location'] == 'Labs') echo 'selected'; ?>>Labs</option>
                            <option value="Clinic-2" <?php if ($row['current_location'] == 'Clinic-2') echo 'selected'; ?>>Clinic 2</option>
                            <option value="Pharmacy" <?php if ($row['current_location'] == 'Pharmacy') echo 'selected'; ?>>Pharmacy</option>
                            <option value="Closed" <?php if ($row['current_location'] == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="display-block">Ticket Status:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_open" value="1"
                                <?php if ($row['status'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label" for="status_open">Open</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_closed" value="0"
                                <?php if ($row['status'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label" for="status_closed">Closed</label>
                        </div>
                    </div>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary submit-btn">Update Ticket</button>
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

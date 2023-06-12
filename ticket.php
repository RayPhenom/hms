<?php
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');
?>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>

<?php
// retrieve all patients from the database
$get_patients_query = mysqli_query($connection, "SELECT * FROM tbl_patient");

// retrieve the last ticket ID
$last_ticket_query = mysqli_query($connection, "SELECT ticket_number FROM tbl_ticket ORDER BY ticket_number DESC LIMIT 1");
$last_ticket = mysqli_fetch_assoc($last_ticket_query);
$last_ticket_number = $last_ticket['ticket_number'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];

    if (empty($patient_id)) {
        echo '<script>Swal.fire("Error", "Please select a patient.", "error");</script>';
    } else {
        // Check if the patient already has an open ticket
        $check_ticket_query = mysqli_query($connection, "SELECT * FROM tbl_ticket WHERE patient_id = '$patient_id' AND status = 1");
        if (mysqli_num_rows($check_ticket_query) > 0) {
            echo '<script>Swal.fire("Error", "Error generating ticket. Patient already has an open ticket.", "error");</script>';
        } else {
            $ticket_id = bin2hex(random_bytes(4));
            $ticket_number = str_pad($last_ticket_number + 1, 6, '0', STR_PAD_LEFT); // generate a 6-digit ticket ID
            $date = date('Y-m-d H:i:s');

            // Save ticket to the ticket table with the status field as "open" and default current location as "Waiting Area"
            $save_ticket_query = mysqli_query($connection, "INSERT INTO tbl_ticket (patient_id, ticket_id, ticket_number, created_at, status, current_location) VALUES ('$patient_id', '$ticket_id', '$ticket_number', '$date', 1, 'Waiting Area')");

            if ($save_ticket_query) {
                echo '<script>Swal.fire("Success", "Ticket generated successfully!", "success");</script>';
            } else {
                echo '<script>Swal.fire("Error", "Error generating ticket. Please try again later.", "error");</script>';
            }
        }
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Generate Next Ticket</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="add-patient.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Patient</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form method="POST">
                    <div class="form-group">
                        <label for="patient_id">Select patient:</label>
                        <select class="form-control" id="patient_id" name="patient_id">
                            <option value="">Select Patient</option>
                            <?php while ($row = mysqli_fetch_array($get_patients_query)) { ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['first_name'] . " " . $row['last_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Ticket</button>
                </form>
            </div>
            <div class="col-md-6">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Last Ticket</h4>
            </div>
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <!-- <th>Ticket ID</th> -->
                                <th>Ticket Number</th>
                                <th>Patient Name</th>
                                <th>Created At</th>
                                <th>Current Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $last_ticket_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_id, tbl_ticket.ticket_number, tbl_patient.first_name, tbl_patient.last_name, tbl_ticket.created_at, tbl_ticket.current_location
                                FROM tbl_ticket 
                                JOIN tbl_patient 
                                ON tbl_ticket.patient_id = tbl_patient.id
                                WHERE tbl_ticket.created_at = (SELECT MAX(created_at) FROM tbl_ticket)");

                            while ($row = mysqli_fetch_array($last_ticket_query)) { ?>

                                <tr>
                                    <td hidden><?php echo $row['ticket_id']; ?></td>
                                    <td><?php echo $row['ticket_number']; ?></td>
                                    <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['current_location']; ?></td>
                                    <td><button class="btn btn-secondary" onclick="printTicket('<?php echo $row['ticket_id']; ?>')">Print Ticket</button></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script>
    function printTicket(ticketId) {
        // Add your code to print the ticket or perform any desired action
        console.log("Printing ticket: " + ticketId);
    }
</script>

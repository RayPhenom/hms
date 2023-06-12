<?php
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');

// Function to check if the user can prescribe medication based on their role
function canPrescribeMedication($role)
{
    return ($role == 2);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $narrative = $_POST['narrative'];
    $doctors_id = $_POST['doctors_id'];
    $diagnosis = $_POST['diagnosis'];
    $date = date('Y-m-d H:i:s');

    // Update the current ticket's location and updated_at fields
    $update_ticket_query = mysqli_query($connection, "UPDATE tbl_ticket SET current_location = 'Clinics', updated_at = '$date' WHERE ticket_id = '$ticket_id'");

    if ($update_ticket_query) {
        // Check if the ticket ID already has consultation data
        $check_consult_query = mysqli_query($connection, "SELECT * FROM tbl_consult WHERE ticket_id = '$ticket_id'");

        if ($check_consult_query) {
            if (mysqli_num_rows($check_consult_query) > 0) {
                $msg = "Consultation data for this ticket already exists.";
            } else {
                // Save consultation data to the consult table with timestamp
                $save_consult_query = mysqli_query($connection, "INSERT INTO tbl_consult (ticket_id, narrative, doctors_id, Diagnosis) VALUES ('$ticket_id', '$narrative', '$doctors_id', '$diagnosis')");

                if ($save_consult_query) {
                    $msg = "Consultation data saved successfully!";
                } else {
                    $msg = "Error saving consultation data: " . mysqli_error($connection);
                }
            }
        } else {
            $msg = "Error executing query: " . mysqli_error($connection);
        }
    } else {
        $msg = "Error updating ticket: " . mysqli_error($connection);
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="col-sm-4 col-3">
                    <h4 class="page-title">Clinic Queue</h4>
                </div>
                <div class="row">
                    <div class="table-responsive">
                    <div class="card-title">Curent Ticket</div>
                        <table class="datatable table table-stripped">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>Created At</th>
                                    <th>Patient</th>
                                    <th>Patient Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $get_tickets_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_id, tbl_ticket.ticket_number, tbl_ticket.current_location, tbl_patient.first_name, tbl_patient.last_name, tbl_patient.patient_type, tbl_ticket.created_at 
                                FROM tbl_ticket 
                                JOIN tbl_patient ON tbl_ticket.patient_id = tbl_patient.id
                                WHERE tbl_ticket.status = 1 AND tbl_ticket.current_location = 'Triage'
                                ORDER BY tbl_ticket.created_at ASC");

                                if (!$get_tickets_query) {
                                    printf("Error: %s\n", mysqli_error($connection));
                                    exit();
                                }

                                if ($row = mysqli_fetch_array($get_tickets_query)) {
                                    $ticket_id = $row['ticket_id'];
                                    $ticket_number = $row['ticket_number'];
                                    ?>
                                    <tr>
                                        <td><?php echo $ticket_number; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                        <td><?php echo $row['patient_type']; ?></td>
                                    </tr>
                                <?php
                                } else {
                                    echo '<tr><td colspan="4">No tickets in the queue.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php if ($row) { ?>
                            <div class="form-group">
                                <form method="POST" action="consultation.php">
                                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Next Ticket</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="table-responsive">
            <div class="card-title">Clinic Queue</div>
            <table class="datatable table table-stripped">
                <thead>
                    <tr>
                        <th>Ticket Number</th>
                        <th>Created At</th>
                        <th>Last Updated At</th>
                        <th>Previous Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                     // retrieve all tickets with patient names from the database
                    $get_queue_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_number, tbl_ticket.ticket_id, tbl_ticket.created_at, tbl_ticket.updated_at, tbl_ticket.status,  tbl_ticket.current_location FROM tbl_ticket 
                    WHERE tbl_ticket.status = 1 AND tbl_ticket.current_location = 'Triage' ORDER BY tbl_ticket.updated_at ASC");
                    while ($queue_row = mysqli_fetch_array($get_queue_query)) { ?>
                        <tr>
                            <td><?php echo $queue_row['ticket_number']; ?></td>
                            <td><?php echo $queue_row['created_at']; ?></td>
                            <td><?php echo $queue_row['updated_at']; ?></td>
                            <td><?php echo $queue_row['current_location']; ?></td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="edit-ticket.php?ticket_number=<?php echo $queue_row['ticket_number']; ?>"><i class="fa fa-ticket m-r-5"></i> Edit Ticket</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Perform Consultation</div>
                        <div class="alert alert-info" role="alert">
                            <?php
                            if (isset($msg)) {
                                echo $msg;
                            }
                            ?>
                        </div>
                        <?php if ($row) { ?>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="ticket_number">Ticket Number</label>
                                    <input type="hidden" name="patient_id" value="<?php echo $ticket_id; ?>">
                                    <input type="text" class="form-control" name="ticket_number" id="ticket_number" value="<?php echo $ticket_number; ?>" readonly>
                                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="narrative">Doctor's Narrative</label>
                                    <textarea class="form-control" name="narrative" id="narrative" rows="3"></textarea>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="doctor_id">Doctor's ID</label>
                                    <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $_SESSION['id']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="doctor_name">Doctor's Name</label>
                                    <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?php echo $_SESSION['name']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="diagnosis">Diagnosis</label>
                                    <textarea class="form-control" name="diagnosis" id="diagnosis" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save Consultation Data</button>
                                </div>
                                <div class="form-group">
                                     <a href="prescription.php?ticket_number=<?php echo $ticket_number; ?>" class="btn btn-primary">Prescription</a>
                                </div>

                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            

<?php include('footer.php'); ?>

<?php
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $temperature = $_POST['temperature'];
    $pressure = $_POST['pressure'];
    $date = date('Y-m-d H:i:s');

    // Update the current ticket's location and updated_at fields
    $update_ticket_query = mysqli_query($connection, "UPDATE tbl_ticket SET current_location = 'Triage', updated_at = '$date' WHERE ticket_id = '$ticket_id'");

    if ($update_ticket_query) {
        // Check if the ticket ID already has triage data
        $check_triage_query = mysqli_query($connection, "SELECT * FROM tbl_triage WHERE ticket_id = '$ticket_id'");

        if ($check_triage_query) {
            if (mysqli_num_rows($check_triage_query) > 0) {
                $msg = "Triage data for this ticket already exists.";
            } else {
                // Save triage data to the triage table with timestamp
                $save_triage_query = mysqli_query($connection, "INSERT INTO tbl_triage (ticket_id, height, weight, temp, pressure, saved_at) VALUES ('$ticket_id', '$height', '$weight', '$temperature', '$pressure', current_timestamp())");

                if ($save_triage_query) {
                    $msg = "Triage data saved successfully!";
                } else {
                    $msg = "Error saving triage data: " . mysqli_error($connection);
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
                    <h4 class="page-title">Triage Queue</h4>
                </div>
                <div class="row">
                    <div class="table-responsive">
                    <div class="card-title">Current Ticket</div>
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
                                WHERE tbl_ticket.status = 1 AND tbl_ticket.current_location = 'Waiting_Area'
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
                                <form method="POST" action="triage.php">
                                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Next Ticket</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="table-responsive">
            <div class="card-title">Triage Queue</div>
            <table class="datatable table table-stripped">
                <thead>
                    <tr>
                        <th>Ticket Number</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Previous Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                     // retrieve all tickets with patient names from the database
                    $get_queue_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_number, tbl_ticket.ticket_id, tbl_ticket.created_at, tbl_ticket.updated_at, tbl_ticket.status,  tbl_ticket.current_location FROM tbl_ticket 
                    WHERE tbl_ticket.status = 1 AND tbl_ticket.current_location = 'Waiting_Area'  ORDER BY tbl_ticket.created_at ASC");
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
                                        <a class="dropdown-item" href="edit-ticket.php?ticket_number=<?php echo $row['ticket_number']; ?>"><i class="fa fa-ticket m-r-5"></i> Edit Ticket</a>
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
                        <div class="card-title">Perform Triage</div>
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
                                <input type="text" class="form-control" name="ticket_number" id="ticket_number" value="<?php echo $ticket_number; ?>" readonly>
                                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                            </div>
                            <div class="form-group">
                                <label for="height">Height (cm)</label>
                                <input type="number" class="form-control" name="height" id="height" required>
                            </div>
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="text" class="form-control" name="weight" id="weight" required>
                            </div>
                            <div class="form-group">
                                <label for="temperature">Temperature (°C)</label>
                                <input type="text" class="form-control" name="temperature" id="temperature" required>
                            </div>
                            <div class="form-group">
                                <label for="pressure">Blood Pressure (mmHg)</label>
                                <input type="text" class="form-control" name="pressure" id="pressure" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Triage Data</button>
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

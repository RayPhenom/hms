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
}
?>

<div class="page-wrapper">
    <div class="content">
    <div class="col-sm-8 text-right m-b-20">
                <a href="triage.php" class="btn btn-primary btn-rounded float-right">Back</a>
            </div>
            <div class="col-md-6">
                <form method="POST">
                    <div class="form-group">
                        <label for="patient_name">Patient Name:</label>
                        <?php
                        $get_tickets_query = mysqli_query($connection, "SELECT tbl_patient.*, tbl_ticket.ticket_id
                                                                         FROM tbl_patient
                                                                         JOIN tbl_ticket
                                                                         ON tbl_patient.id = tbl_ticket.patient_id
                                                                         WHERE tbl_ticket.status = 1
                                                                         LIMIT 1");

                        $row = mysqli_fetch_array($get_tickets_query);
                        ?>
                        <input class="form-control" type="text" id="patient_name" name="patient_name" value="<?php echo $row['first_name'] . " " . $row['last_name']; ?>" readonly>

                        <!-- Hidden input field for ticket ID -->
                        <input type="hidden" name="ticket_id" value="<?php echo $row['ticket_id']; ?>">

                        <label for="height">Height:</label><br>
                        <input class="form-control" type="text" id="height" name="height"><br>
                        <label for="weight">Weight:</label><br>
                        <input class="form-control" type="text" id="weight" name="weight"><br>
                        <label for="temperature">Temperature:</label><br>
                        <input class="form-control" type="text" id="temperature" name="temperature">
                        <label for="pressure">Blood Pressure:</label><br>
                        <input class="form-control" type="text" id="pressure" name="pressure">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Triage Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    <?php
    if (isset($msg)) {
        echo 'Swal.fire("' . $msg . '");';
    }
    ?>
</script>

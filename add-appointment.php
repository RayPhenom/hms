<?php
session_start();
if(empty($_SESSION['name']) || $_SESSION['role']!=1)
{
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');
    
      $fetch_query = mysqli_query($connection, "select max(id) as id from tbl_appointment");
      $row = mysqli_fetch_row($fetch_query);
      if($row[0]==0)
      {
        $apt_id = 1;
      }
      else
      {
        $apt_id = $row[0] + 1;
      }
    if(isset($_REQUEST['add-appointment']))
    {
      
      $appointment_id = 'APT-'.$apt_id;
      $patient_name = $_REQUEST['patient_name'];
      $department = $_REQUEST['department'];
      $doctor = $_REQUEST['doctor'];
      $date = $_REQUEST['date'];
      $time = $_REQUEST['time'];
      $message = $_REQUEST['message'];
      $status = $_REQUEST['status'];

      
      $insert_query = mysqli_query($connection, "insert into tbl_appointment set appointment_id='$appointment_id', patient_name='$patient_name', department='$department', doctor='$doctor', date='$date',  time='$time', message='$message', status='$status'");

      if($insert_query>0)
      {
          $msg = "Appointment created successfully";
      }
      else
      {
          $msg = "Error!";
      }
    }
?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 ">
                        <h4 class="page-title">Add Appointment</h4>
                         
                    </div>
                    <div class="col-sm-8  text-right m-b-20">
                        <a href="appointments.php" class="btn btn-primary btn-rounded float-right">Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form method="post" >
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Appointment ID <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="appointment_id" value="<?php if(!empty($apt_id)) { echo 'APT-'.$apt_id; } else { echo "APT-1"; } ?>" disabled> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Patient Name</label>
                                        <select class="select" name="patient_name" required>
                                            <option value="">Select</option>
                                        <?php
                                        $fetch_query = mysqli_query($connection, "select concat(first_name,' ',last_name) as name, dob from tbl_patient");
                                        while($row = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            
                                            <option value="<?php echo $row['name']; ?>,<?php echo $row['dob']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="select" name="department" required>
                                            <option value="">Select</option>
                                        <?php
                                        $fetch_query = mysqli_query($connection, "select department_name from tbl_department");
                                        while($row = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            <option><?php echo $row['department_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Doctor</label>
                                        <select class="select" name="doctor" required>
                                            <option value="">Select</option>
                                            <?php
                                        $fetch_query = mysqli_query($connection, "select concat(first_name,' ',last_name) as name from tbl_employee where role=2 and status=1");
                                        while($row = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            <option><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" name="date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <div class="time-icon">
                                            <input type="text" class="form-control" id="datetimepicker3" name="time" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="form-group">
                                <label>Message</label>
                                <textarea cols="30" rows="4" class="form-control" name="message" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="display-block">Appointment Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="product_active" value="1" checked>
                                    <label class="form-check-label" for="product_active">
                                    Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="product_inactive" value="0">
                                    <label class="form-check-label" for="product_inactive">
                                    Inactive
                                    </label>
                                </div>
                            </div>
                             
                            <div class="m-t-20 text-center">
                                <button name="add-appointment" class="btn btn-primary submit-btn">Create Appointment</button>
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
        if(isset($msg)) {
            echo 'swal("' . $msg . '");';
        }
    ?>
</script>
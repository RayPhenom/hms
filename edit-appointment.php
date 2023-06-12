<?php 
session_start();
if(empty($_SESSION['name']))
{
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');

$id = $_GET['id'];
$fetch_query = mysqli_query($connection, "select * from tbl_appointment where id='$id'");
$row = mysqli_fetch_array($fetch_query);

if(isset($_REQUEST['save-appointment']))
{
      $appointment_id = $_REQUEST['appointment_id'];
      $patient_name = $_REQUEST['patient_name'];
      $department = $_REQUEST['department'];
      $doctor = $_REQUEST['doctor'];
      $date = $_REQUEST['date'];
      $time = $_REQUEST['time'];
      $message = $_REQUEST['message'];
      $status = $_REQUEST['status'];


      $update_query = mysqli_query($connection, "update tbl_appointment set appointment_id='$appointment_id', patient_name='$patient_name', department='$department', doctor='$doctor', date='$date',  time='$time', message='$message', status='$status' where id='$id'");
      if($update_query>0)
      {
          $msg = "Appointment updated successfully";
          $fetch_query = mysqli_query($connection, "select * from tbl_appointment where id='$id'");
          $row = mysqli_fetch_array($fetch_query);   
          
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
                        <h4 class="page-title">Edit Appointment</h4>
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
                                        <input class="form-control" type="text" name="appointment_id" value="<?php  echo $row['appointment_id'];  ?>"> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Patient Name</label>
                                        <select class="select" name="patient_name" >
                                            <option value="">Select</option>
                                        <?php
                                        $fetch_query = mysqli_query($connection, "select * from tbl_appointment where id='$id'");
                                        $row = mysqli_fetch_array($fetch_query);   
                                        $pat_name = explode(",", $row['patient_name']);
                                        $pat_name = $pat_name[0];

                                        $fetch_query = mysqli_query($connection, "select concat(first_name,' ',last_name) as name  from tbl_patient");
                                        while($rows = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            
                                    <option <?php if($rows['name'] == $pat_name) { ?> selected="selected"; <?php } ?>><?php echo $rows['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="select" name="department" >
                                            <option value="">Select</option>
                                            <?php
                                        $fetch_query = mysqli_query($connection, "select department_name from tbl_department");
                                        while($dept = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            <option <?php if($dept['department_name']==$row['department'] ) { ?> selected="selected"; <?php } ?>><?php echo $dept['department_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Doctor</label>
                                        <select class="select" name="doctor" >
                                            <option value="">Select</option>
                                            <?php
                                        $fetch_query = mysqli_query($connection, "select concat(first_name,' ',last_name) as name from tbl_employee where role=2 and status=1");
                                        while($doc = mysqli_fetch_array($fetch_query)){
                                        ?>
                                            <option <?php if($doc['name']==$row['doctor'] ) { ?> selected="selected"; <?php } ?>><?php echo $doc['name']; ?></option>
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
                                            <input type="text" class="form-control datetimepicker" name="date" value="<?php  echo $row['date'];  ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <div class="time-icon">
                                            <input type="text" class="form-control" id="datetimepicker3" name="time" value="<?php  echo $row['time'];  ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="form-group">
                                <label>Message</label>
                                <textarea cols="30" rows="4" class="form-control" name="message" required><?php  echo $row['message'];  ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="display-block">Appointment Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="product_active" value="1" <?php if($row['status']==1) { echo 'checked' ; } ?>>
                                    <label class="form-check-label" for="product_active">
                                    Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="product_inactive" value="0" <?php if($row['status']==0) { echo 'checked' ; } ?>>
                                    <label class="form-check-label" for="product_inactive">
                                    Inactive
                                    </label>
                                </div>
                            </div>
                             
                            <div class="m-t-20 text-center">
                                <button name="save-appointment" class="btn btn-primary submit-btn">Save</button>
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
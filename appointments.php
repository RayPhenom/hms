<?php
session_start();
if(empty($_SESSION['name']))
{
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');
?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Appointments</h4>
                    </div>
                    <?php 
                    if($_SESSION['role']==1){?>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="add-appointment.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Appointment</a>
                    </div>
                <?php } ?>
                </div>
                <div class="table-responsive">
                                    <table class="datatable table table-stripped ">
                                    <thead>
                                        <tr>
                                            <th>Appointment ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Doctor Name</th>
                                            <th>Department</th>
                                            <th>Appointment Date</th>
                                            <th>Appointment Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($_GET['ids'])){
                                        $id = $_GET['ids'];
                                        $delete_query = mysqli_query($connection, "delete from tbl_appointment where id='$id'");
                                        }
                                        $fetch_query = mysqli_query($connection, "select * from tbl_appointment");
                                        while($row = mysqli_fetch_array($fetch_query))
                                        {
                                            
                                            $time = strtotime($row['time']);
                                            $time = date('h:i', $time);
                                            $timestamp = strtotime($time) + 60*60;
                                            $timeout = date('h:i', $timestamp);

                                            $name = explode(",", $row['patient_name']);
                                            $name = $name[0];

                                            $age = explode(",", $row['patient_name']);
                                            $age = $age[1] ?? null;

                                            $date = str_replace('/', '-', $age); 
                                            $dob = date('Y-m-d', strtotime($date));
                                            $year = (date('Y') - date('Y',strtotime($dob)));
                                        ?>
                                        <tr>
                                        <td><?php echo $row['appointment_id']; ?></td>
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $year; ?></td>
                                            <td><?php echo $row['doctor']; ?></td>
                                            <td><?php echo $row['department']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $time.' - '.$timeout; ?></td>
                                            <?php if($row['status']==1) { ?>
                                            <td><span class="custom-badge status-green">Active</span></td>
                                        <?php } else {?>
                                            <td><span class="custom-badge status-red">Inactive</span></td>
                                        <?php } ?>
                                            <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="edit-appointment.php?id=<?php echo $row['id'];?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="appointments.php?ids=<?php echo $row['id'];?>" onclick="return confirmDelete()"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
		
   
<?php
include('footer.php');
?>
<script language="JavaScript" type="text/javascript">
function confirmDelete(){
    return confirm('Are you sure want to delete this Appointments?');
}
</script>
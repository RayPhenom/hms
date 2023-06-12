<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>HMS</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body>
    <div class="main-wrapper">
        <div class="header">
			<div class="header-left">
				<a href="dashboard.php" class="logo">
					<img src="assets/img/logo.png" width="35" height="35" alt=""> <span>HMS</span>
				</a>
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                   <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="assets/img/user.jpg" width="24" alt="Admin">
							<span class="status online"></span>
						</span>
                        <?php 
                        if($_SESSION['role']==1){ ?>
						<span>Admin</span>
                    <?php } else {?>
                        <span><?php echo $_SESSION['name']; ?></span>
                    <?php } ?>
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="logout.php">Logout</a>
					</div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <?php
                    
                    if($_SESSION['role']==1){?>
                    <ul>
                        
                        <li class="active">
                            <a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="ticket.php"><i class="fa fa-ticket"></i> <span>Tickets</span></a>
                        </li>
                        <li>
                            <a href="queue.php"><i class="fa fa-stethoscope"></i> <span>Queue</span></a>
                        </li>
                    
                        
                        <li>
                            <a href="triage.php"><i class="fa fa-heartbeat"></i> <span>Triage</span></a>
                        </li>
                        <li>
                            <a href="consultation.php"><i class="fa  fa-plus-square"></i> <span>Clinic</span></a>
                        </li>
                        <li>
                            <a href="laboratory.php"><i class="fa fa-medkit"></i> <span>Lab</span></a>
                        </li>
                        <li>
                            <a href="post-lab.php"><i class="fa fa-medkit"></i> <span>Clinic 2</span></a>
                        </li>
                        <li>
                            <a href="pharmacy.php"><i class="fa fa-medkit"></i> <span>Pharmacy</span></a>
                        </li>
                         
						<li>
                            <a href="doctors.php"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                    
                        <li>
                            <a href="patients.php"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="schedule.php"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                        </li>
                        <li>
                            <a href="departments.php"><i class="fa fa-hospital-o"></i> <span>Departments</span></a>
                        </li>
                        <li>
                            <a href="employees.php"><i class="fa fa-user"></i> <span>Employees</span></a>
                        </li>
                       
                        							                       
                    </ul>
                <?php } else {?>
                    <ul>
                        
                        <li class="active">
                            <a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="ticket.php"><i class="fa fa-ticket"></i> <span>Tickets</span></a>
                        </li>
                        <li>
                            <a href="queue.php"><i class="fa fa-stethoscope"></i> <span>Queue</span></a>
                        </li>
                    
                        
                        <li>
                            <a href="triage.php"><i class="fa fa-heartbeat"></i> <span>Triage</span></a>
                        </li>
                        <li>
                            <a href="consultation.php"><i class="fa  fa-plus-square"></i> <span>Clinic</span></a>
                        </li>
                        <li>
                            <a href="laboratory.php"><i class="fa fa-medkit"></i> <span>Lab</span></a>
                        </li>
                        <li>
                            <a href="post-lab.php"><i class="fa fa-medkit"></i> <span>Clinic 2</span></a>
                        </li>
                        <li>
                            <a href="pharmacy.php"><i class="fa fa-medkit"></i> <span>Pharmacy</span></a>
                        </li>
                        <li>
                            <a href="patients.php"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                                                                       
                    </ul>
                <?php } ?>
                </div>
            </div>
      </div>
</div>

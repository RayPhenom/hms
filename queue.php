<?php
session_start();
if(empty($_SESSION['name']))
{
    header('location:index.php');
}
include('header.php');
include('includes/connection.php');

// retrieve all tickets with patient names from database
$get_tickets_query = mysqli_query($connection, "SELECT tbl_ticket.ticket_number, tbl_ticket.ticket_id, tbl_ticket.created_at, tbl_ticket.status,  tbl_ticket.current_location FROM tbl_ticket ");
?>



<div class="page-wrapper">
    <div class="content">
        <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">All Tickets</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="ticket.php" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> New Ticket</a>
                    </div>
                <div class="table-responsive">
                    <table class="datatable table table-stripped ">
                        <thead>
                            <tr>
                                <th>Ticket Number</th>
                                <th>Created At</th>
                                <th>Previous Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($get_tickets_query)) { ?>
                                <tr>
                                    <td><?php echo $row['ticket_number']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['current_location']; ?></td>
                                    <?php if($row['status']==1) { ?>
                                            <td><span class="custom-badge status-green">Open</span></td>
                                        <?php } else {?>
                                            <td><span class="custom-badge status-red">Closed</span></td>
                                        <?php } ?>
                                   

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
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
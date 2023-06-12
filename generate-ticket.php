<?php
session_start();
if(empty($_SESSION['name']))
{
    header('location:index.php');
}
include('includes/connection.php');

// Get the patient ID from the form
$patientId = $_POST['patientId'];

// Generate an 8-digit uuid ticket number
$ticketNumber = bin2hex(random_bytes(4));

// Save the ticket to the ticket table
$insert_query = mysqli_query($connection, "INSERT INTO tbl_ticket (patient_id, ticket_number) VALUES ('$patientId', '$ticketNumber')");

// Redirect back to the patients page
header('location:patients.php');
?>

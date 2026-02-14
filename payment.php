<?php
session_start();
include "db.php";

$id = $_SESSION['student_id'];

$p = $conn->query("SELECT * FROM payments WHERE student_id=$id");
$payment = $p->fetch_assoc();
?>

<h2>Payment Status</h2>

Status:
<?php
if($payment){
echo $payment['status'];
}else{
echo "Not Paid";
}
?>

<a href="dashboard.php">Back</a>

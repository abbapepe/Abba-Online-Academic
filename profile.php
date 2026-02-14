<?php
session_start();
include "db.php";

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['student_id'];
$res = $conn->query("SELECT * FROM applications WHERE id='$id'");
$student = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<h3>My Profile</h3>
<table class="table table-bordered mt-3">
<tr><th>Full Name</th><td><?= $student['fullname']; ?></td></tr>
<tr><th>Email</th><td><?= $student['email']; ?></td></tr>
<tr><th>Phone</th><td><?= $student['phone']; ?></td></tr>
<tr><th>Course</th><td><?= $student['course']; ?></td></tr>
<tr><th>Status</th><td><?= $student['status']; ?></td></tr>
<tr><th>DOB</th><td><?= $student['dob']; ?></td></tr>
</table>
<a href="dashboard.php" class="btn btn-primary">Back</a>
</div>
</body>
</html>

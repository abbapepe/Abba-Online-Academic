<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header("Location: student_login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?>!</h3>
    <p>This is your student portal.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</body>
</html>

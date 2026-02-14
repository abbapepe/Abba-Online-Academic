<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['student_id'];
$result = $mysqli->query("SELECT * FROM students WHERE id='$id'");
$student = $result->fetch_assoc();
?>

<h2>Welcome, <?php echo $student['fullname']; ?></h2>
<p>Email: <?php echo $student['email']; ?></p>
<p>Course: <?php echo $student['course']; ?></p>
<p>Status: <?php echo $student['status']; ?></p>

<a href="logout.php">Logout</a>

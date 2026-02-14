<?php
session_start();
include 'db.php';

$id = $_SESSION['student_id'];

$student = $mysqli->query("SELECT * FROM students WHERE id='$id'")->fetch_assoc();
?>

<h2>Admission Letter</h2>

<p>Dear <?= $student['fullname'] ?>,</p>

<p>
Congratulations! You have been admitted into
Bene Royal College of Health Science and Technology
to study <?= $student['course'] ?>.
</p>

<p>Level: <?= $student['level'] ?></p>

<button onclick="window.print()">Download Letter</button>


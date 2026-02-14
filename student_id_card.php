<?php
session_start();
include 'db.php';

$id = $_SESSION['student_id'];

$student = $mysqli->query("SELECT * FROM students WHERE id='$id'")->fetch_assoc();
?>

<style>
.card{
width:350px;
border:2px solid black;
padding:20px;
}
</style>

<div class="card">

<h3>Bene Royal College</h3>

<p>Name: <?= $student['fullname'] ?></p>
<p>Course: <?= $student['course'] ?></p>
<p>Level: <?= $student['level'] ?></p>
<p>ID: BCHT<?= $student['id'] ?></p>

</div>

<button onclick="window.print()">Print ID Card</button>

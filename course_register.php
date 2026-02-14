<?php
session_start();
include 'db.php';

$id = $_SESSION['student_id'];

if(isset($_POST['register'])){

    $course = $_POST['course'];
    $session = "2025/2026";

    $student = $mysqli->query("SELECT level FROM students WHERE id='$id'")->fetch_assoc();

    $mysqli->query("INSERT INTO course_registration(student_id,course_name,session,level)
    VALUES('$id','$course','$session','{$student['level']}')");
}
?>

<form method="POST">
<select name="course">

<option>Community Health</option>
<option>Medical Lab Technology</option>
<option>Pharmacy Technician</option>

</select>

<button name="register">Register Course</button>
</form>

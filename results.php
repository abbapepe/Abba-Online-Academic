<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$results = $mysqli->query("SELECT * FROM student_results WHERE student_id='$student_id'");
?>

<h3>Your Results</h3>
<table border="1" cellpadding="10">
<tr>
    <th>Course</th>
    <th>Score</th>
    <th>Grade</th>
    <th>Level</th>
    <th>Session</th>
</tr>
<?php while($row = $results->fetch_assoc()): ?>
<tr>
    <td><?= $row['course_name']; ?></td>
    <td><?= $row['score']; ?></td>
    <td><?= $row['grade']; ?></td>
    <td><?= $row['level']; ?></td>
    <td><?= $row['session']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<a href="download_results.php" target="_blank">Download PDF</a>

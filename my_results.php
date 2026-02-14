<?php
session_start();
include "db.php";
if(!isset($_SESSION['student_id'])){ header("Location: login.php"); exit; }

$id = $_SESSION['student_id'];
$res = $conn->query("SELECT * FROM applications WHERE id='$id'");
$student = $res->fetch_assoc();
$results = json_decode($student['olevel_json'], true);
?>
<!DOCTYPE html>
<html>
<head>
<title>Results</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<h3>My Results</h3>
<?php
if(!$results){
    echo "<p>No results uploaded yet.</p>";
}else{
?>
<h5>First Sitting</h5>
<table class="table table-bordered">
<tr>
<th>Subject</th><th>Grade</th>
</tr>
<?php
foreach($results['first_sitting'] as $sub=>$grade){
    if($sub == 'others'){
        echo "<tr><td>".$grade['subject']."</td><td>".$grade['grade']."</td></tr>";
    }else{
        echo "<tr><td>$sub</td><td>$grade</td></tr>";
    }
}
?>
</table>

<h5>Second Sitting</h5>
<table class="table table-bordered">
<tr>
<th>Subject</th><th>Grade</th>
</tr>
<?php
foreach($results['second_sitting'] as $sub=>$grade){
    if($sub == 'others'){
        echo "<tr><td>".$grade['subject']."</td><td>".$grade['grade']."</td></tr>";
    }else{
        echo "<tr><td>$sub</td><td>$grade</td></tr>";
    }
}
?>
</table>
<?php } ?>
<a href="dashboard.php" class="btn btn-primary">Back</a>
</div>
</body>
</html>

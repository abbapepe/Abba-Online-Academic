<?php
session_start();
include 'db.php';
require 'vendor/autoload.php';
use Dompdf\Dompdf;

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$student_res = $mysqli->query("SELECT * FROM student_results WHERE student_id='$student_id'");

$html = "<h2>Results</h2><table border='1' cellpadding='5'><tr><th>Course</th><th>Score</th><th>Grade</th><th>Level</th><th>Session</th></tr>";

while($row = $student_res->fetch_assoc()){
    $html .= "<tr>
                <td>{$row['course_name']}</td>
                <td>{$row['score']}</td>
                <td>{$row['grade']}</td>
                <td>{$row['level']}</td>
                <td>{$row['session']}</td>
              </tr>";
}

$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("results.pdf");

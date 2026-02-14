<?php
include 'db.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST['upload'])){
    $file = $_FILES['results_file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    foreach($sheet as $index => $row){
        if($index == 0) continue; // skip header
        $student_email = $row[0];
        $course = $row[1];
        $score = $row[2];
        $level = $row[3];
        $session = $row[4];

        $res = $mysqli->query("SELECT id FROM students WHERE email='$student_email'");
        if($res->num_rows > 0){
            $student = $res->fetch_assoc();
            $grade = '';
            if($score >= 70) $grade = 'A';
            elseif($score >= 60) $grade = 'B';
            elseif($score >= 50) $grade = 'C';
            elseif($score >= 45) $grade = 'D';
            else $grade = 'F';

            $mysqli->query("INSERT INTO student_results (student_id, course_name, score, grade, level, session) 
                            VALUES ('{$student['id']}', '$course', '$score', '$grade', '$level', '$session')");
        }
    }

    echo "Results uploaded successfully!";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="results_file" accept=".xlsx,.xls">
    <button type="submit" name="upload">Upload Results</button>
</form>

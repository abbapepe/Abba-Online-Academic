<?php
include 'db.php';

// Loop all students
$students = $mysqli->query("SELECT * FROM students");

while($student = $students->fetch_assoc()){

    $id = $student['id'];
    $level = $student['level'];

    // Calculate GPA
    $results = $mysqli->query("SELECT score FROM student_results WHERE student_id='$id'");

    $total = 0;
    $count = 0;

    while($r = $results->fetch_assoc()){
        $total += $r['score'];
        $count++;
    }

    if($count > 0){
        $avg = $total / $count;

        // Promote if average >= 50
        if($avg >= 50){
            $new_level = $level + 1;
            $mysqli->query("UPDATE students SET level='$new_level' WHERE id='$id'");
        }
    }
}

echo "Promotion complete!";
?>

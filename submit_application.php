<?php
session_start();
include 'db_connect.php';

// File upload directory
$upload_dir = "uploads/";

// Helper function to upload file
function upload_file($file, $dir){
    $target_file = $dir . time() . "_" . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $target_file;
    } else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape all POST inputs
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email    = $conn->real_escape_string($_POST['email']);
    $phone    = $conn->real_escape_string($_POST['phone']);
    $dob      = $conn->real_escape_string($_POST['dob']);
    $gender   = $conn->real_escape_string($_POST['gender']);
    $state    = $conn->real_escape_string($_POST['state']);
    $lga      = $conn->real_escape_string($_POST['lga']);
    $address  = $conn->real_escape_string($_POST['address']);
    $school   = $conn->real_escape_string($_POST['school']);
    $qualification = $conn->real_escape_string($_POST['qualification']);
    $course   = $conn->real_escape_string($_POST['course']);

    $english1 = $_POST['english1'] ?? null;
    $maths1 = $_POST['maths1'] ?? null;
    $chemistry1 = $_POST['chemistry1'] ?? null;
    $physics1 = $_POST['physics1'] ?? null;
    $biology1 = $_POST['biology1'] ?? null;
    $other_subject1 = $_POST['other_subject1'] ?? null;
    $other_grade1 = $_POST['other_grade1'] ?? null;

    $english2 = $_POST['english2'] ?? null;
    $maths2 = $_POST['maths2'] ?? null;
    $chemistry2 = $_POST['chemistry2'] ?? null;
    $physics2 = $_POST['physics2'] ?? null;
    $biology2 = $_POST['biology2'] ?? null;
    $other_subject2 = $_POST['other_subject2'] ?? null;
    $other_grade2 = $_POST['other_grade2'] ?? null;

    // Upload files
    $passport = upload_file($_FILES['passport'], $upload_dir);
    $olevel = upload_file($_FILES['olevel'], $upload_dir);
    $primarycert = upload_file($_FILES['primarycert'], $upload_dir);
    $indigene = upload_file($_FILES['indigene'], $upload_dir);
    $birthcert = upload_file($_FILES['birthcert'], $upload_dir);

    // Insert into applications table
    $sql = "INSERT INTO applications (
        fullname,email,phone,dob,gender,state,lga,address,school,qualification,course,
        english1,maths1,chemistry1,physics1,biology1,other_subject1,other_grade1,
        english2,maths2,chemistry2,physics2,biology2,other_subject2,other_grade2,
        passport,olevel,primarycert,indigene,birthcert
    ) VALUES (
        '$fullname','$email','$phone','$dob','$gender','$state','$lga','$address','$school','$qualification','$course',
        '$english1','$maths1','$chemistry1','$physics1','$biology1','$other_subject1','$other_grade1',
        '$english2','$maths2','$chemistry2','$physics2','$biology2','$other_subject2','$other_grade2',
        '$passport','$olevel','$primarycert','$indigene','$birthcert'
    )";

    if($conn->query($sql)){
        $_SESSION['success'] = "Application submitted successfully! Wait for admin approval.";
        header("Location: apply.html");
        exit();
    } else {
        $_SESSION['error'] = "Error: ".$conn->error;
        header("Location: apply.html");
        exit();
    }
}
?>

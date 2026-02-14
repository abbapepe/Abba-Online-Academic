<?php
session_start();
include "db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $lga = $_POST['lga'];
    $address = $conn->real_escape_string($_POST['address']);
    $school = $conn->real_escape_string($_POST['school']);
    $qualification = $_POST['qualification'];
    $course = $_POST['course'];

    // Handle file uploads
    $uploads = [];
    if(isset($_FILES['passport'])){
        $passport_name = time().'_'.basename($_FILES['passport']['name']);
        move_uploaded_file($_FILES['passport']['tmp_name'], "uploads/".$passport_name);
        $uploads['passport'] = "uploads/".$passport_name;
    }
    if(isset($_FILES['olevel'])){
        $olevel_name = time().'_'.basename($_FILES['olevel']['name']);
        move_uploaded_file($_FILES['olevel']['tmp_name'], "uploads/".$olevel_name);
        $uploads['olevel'] = "uploads/".$olevel_name;
    }

    $uploads_json = json_encode($uploads);

    // Insert into applications table
    $sql = "INSERT INTO applications (fullname,email,phone,dob,gender,state,lga,address,school,qualification,course,uploads_json,status)
            VALUES ('$fullname','$email','$phone','$dob','$gender','$state','$lga','$address','$school','$qualification','$course','$uploads_json','pending')";

    if($conn->query($sql)){
        $_SESSION['success'] = "Application submitted successfully. Wait for admin approval.";
        header("Location: student_register.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: ".$conn->error;
        header("Location: student_register.php");
        exit;
    }
} else {
    header("Location: student_register.php");
    exit;
}
?>

<?php
session_start();
include "db.php";

if($_SERVER['REQUEST_METHOD']=="POST"){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $student = $result->fetch_assoc();
        if(password_verify($password, $student['password'])){
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['fullname'];
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        $_SESSION['error'] = "Email not found";
    }
    header("Location: login.php");
    exit;
}
?>

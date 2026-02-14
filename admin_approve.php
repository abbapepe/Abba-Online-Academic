<?php
include 'db_connect.php';

if(isset($_GET['app_id'])) {
    $app_id = intval($_GET['app_id']);

    // Fetch application
    $app = $conn->query("SELECT * FROM applications WHERE id=$app_id AND status='pending'")->fetch_assoc();

    if($app){
        // Generate random password
        $password_plain = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),0,8);
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        // Insert into students table
        $sql = "INSERT INTO students (fullname,email,phone,dob,gender,state,lga,address,course,password)
                VALUES (
                    '{$app['fullname']}','{$app['email']}','{$app['phone']}','{$app['dob']}',
                    '{$app['gender']}','{$app['state']}','{$app['lga']}','{$app['address']}','{$app['course']}','$password_hashed'
                )";

        if($conn->query($sql)){
            // Update application status to approved
            $conn->query("UPDATE applications SET status='approved' WHERE id=$app_id");

            echo "Student approved! Email/WhatsApp them the password: $password_plain";
        }
    }
}
?>

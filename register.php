<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape form inputs
    $fullname = $mysqli->real_escape_string($_POST['fullname']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $lga = $_POST['lga'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Check if email exists
    $check = $mysqli->query("SELECT * FROM students WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Insert student
        $insert = $mysqli->query("INSERT INTO students (fullname,email,phone,dob,gender,state,lga,address,course,password) VALUES ('$fullname','$email','$phone','$dob','$gender','$state','$lga','$address','$course','$password')");
        if ($insert) {
            echo "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . $mysqli->error;
        }
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    Full Name: <input type="text" name="fullname" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input type="text" name="phone" required><br>
    DOB: <input type="date" name="dob" required><br>
    Gender: 
    <select name="gender" required>
        <option value="">--Select--</option>
        <option>Male</option>
        <option>Female</option>
    </select><br>
    State: <input type="text" name="state" required><br>
    LGA: <input type="text" name="lga" required><br>
    Address: <input type="text" name="address" required><br>
    Course: 
    <select name="course" required>
        <option value="">--Select--</option>
        <option>Community Health</option>
        <option>Medical Laboratory Technology</option>
        <option>Pharmacy Technician</option>
        <option>Public Health Technology</option>
        <option>Health Information Management</option>
    </select><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>

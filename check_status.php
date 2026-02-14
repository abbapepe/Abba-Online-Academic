<?php
// check_status.php

include 'db_connect.php'; // make sure this file contains your $conn = new mysqli(...) setup

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST['identifier']);

    // Check by email or phone
    $stmt = $conn->prepare("SELECT fullname, status FROM applications WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Check Status - Abba Online Academic</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
      <style>
        :root { --primary-color: #b43b3b; }
        body { background-color: #f8f9fa; }
        .navbar { background-color: var(--primary-color); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: 500; }
        .navbar-brand img { height: 40px; margin-right: 10px; }
        .result-card {
          background: #fff; border-radius: 15px; padding: 40px; text-align: center;
          box-shadow: 0 0 15px rgba(0,0,0,0.1); margin-top: 60px;
        }
        footer { background-color: var(--primary-color); color: #fff; text-align: center; padding: 20px 0; margin-top: 60px; }
      </style>
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.html">
          <img src="assets/img/logo.png" alt="College Logo">
          Bene Royal College of Health Technology
        </a>
      </div>
    </nav>

    <div class="container">
      <div class="result-card mx-auto col-md-8 col-lg-6">';

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = htmlspecialchars($row['fullname']);
        $status = strtolower($row['status']);

        if ($status === 'approved') {
            echo "<h3 class='text-success'>Congratulations, $fullname!</h3>
                  <p>You have been offered admission into Abba Online Academic.</p>";
        } elseif ($status === 'denied') {
            echo "<h3 class='text-danger'>Sorry, $fullname!</h3>
                  <p>Kindly try again next year. We appreciate your interest.</p>";
        } elseif ($status === 'pending') {
            echo "<h3 class='text-warning'>Hello, $fullname!</h3>
                  <p>Sorry, admission is not yet ready. Please check back later.</p>";
        } else {
            echo "<h3 class='text-secondary'>No status information available.</h3>
                  <p>Your record exists but admission status could not be determined.</p>";
        }
    } else {
        echo "<h3 class='text-danger'>No record found!</h3>
              <p>We couldn’t find any application using that email or phone number.</p>";
    }

    echo '<div class="mt-4">
            <a href="check_status.html" class="btn btn-danger">Go Back</a>
          </div>
      </div>
    </div>

    <footer>
      <div class="container">
        <p>&copy; 2025 Abba Online Academic | All Rights Reserved</p>
      </div>
    </footer>

    </body>
    </html>';

    $stmt->close();
    $conn->close();
}
?>

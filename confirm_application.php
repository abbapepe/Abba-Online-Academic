<?php
// confirm_application.php
// This page displays after a successful application submission

// Fetch applicant info from URL (passed via redirect after submission)
$fullname = isset($_GET['fullname']) ? htmlspecialchars($_GET['fullname']) : "Applicant";
$passport = isset($_GET['passport']) ? htmlspecialchars($_GET['passport']) : "assets/img/default_avatar.jpg";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Confirmation - Abba Online Academic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #b43b3b;
    }
    body {
      background-color: #f8f9fa;
    }
    /* Navbar */
    .navbar {
      background-color: var(--primary-color);
    }
    .navbar-brand, .nav-link {
      color: #fff !important;
      font-weight: 500;
    }
    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }
    .nav-link:hover {
      text-decoration: underline;
    }
    /* Confirmation card */
    .confirmation-card {
      background: #fff;
      border-radius: 15px;
      padding: 40px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      margin-top: 50px;
    }
    .confirmation-card img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--primary-color);
      margin-bottom: 20px;
    }
    .confirmation-card h3 {
      color: var(--primary-color);
      font-weight: 700;
    }
    .confirmation-card p {
      font-size: 1.1rem;
      color: #333;
    }
    /* Footer */
    footer {
      background-color: var(--primary-color);
      color: #fff;
      padding: 20px 0;
      text-align: center;
      margin-top: 60px;
    }
    footer a {
      color: #fff;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- HEADER / NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.html">
        <img src="assets/img/logo.png" alt="College Logo">
        Abba Online Academic
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="apply.html">Apply</a></li>
          <li class="nav-item"><a class="nav-link" href="check_status.html">Check Status</a></li>
          <li class="nav-item"><a class="nav-link" href="news.html">News</a></li>
          <li class="nav-item"><a class="nav-link" href="Student-portal.html">Student</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- CONFIRMATION CONTENT -->
  <div class="container">
    <div class="confirmation-card mx-auto col-md-8 col-lg-6">
      <img src="<?php echo $passport; ?>" alt="Applicant Passport">
      <h3><?php echo $fullname; ?></h3>
      <p>Congratulations! Your application has been successfully submitted for approval.</p>
      <p>You will be notified when to check for admission.</p>
      <div class="mt-4">
        <a href="check_status.html" class="btn btn-success px-4">Check Admission Status</a>
        <a href="index.html" class="btn btn-outline-danger px-4 ms-2">Go Home</a>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer>
    <div class="container">
      <p>&copy; 2025 Abba Online Academic | All Rights Reserved</p>
      <div>
        <a href="#"><i class="bi bi-facebook me-3"></i></a>
        <a href="#"><i class="bi bi-twitter me-3"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch basic statistics
$stats = [
    'applicants' => $conn->query("SELECT COUNT(*) AS c FROM applications")->fetch_assoc()['c'],
    'pending' => $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='pending'")->fetch_assoc()['c'],
    'approved' => $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='approved'")->fetch_assoc()['c'],
    'notices' => $conn->query("SELECT COUNT(*) AS c FROM notices")->fetch_assoc()['c']
];

// Fetch latest applicants
$latest_applicants = $conn->query("SELECT id, fullname, course, status FROM applications ORDER BY id DESC LIMIT 6");

// Fetch latest notices
$latest_notices = $conn->query("SELECT id, title, created_at FROM notices ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Abba Online Academic</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    body { background: #f4f4f4; }
    .admin-header { background:#b43b3b; }
    .admin-header a { color:white !important; font-weight:500; }
    .card-stats { border-left:5px solid #b43b3b; }
    .shortcut-btn { border-radius:10px; }
    .shortcut-btn:hover { opacity:0.8; }
</style>
</head>

<body>

<!-- =======================
       ADMIN HEADER
======================= -->
<nav class="navbar navbar-expand-lg navbar-dark admin-header">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="admin_dashboard.php">
      <i class="bi bi-speedometer2"></i> Admin Dashboard
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_applicants.php">Applicants</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_notices.php">Notices</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_courses.php">Courses</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_payments.php">Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_settings.php">Settings</a></li>

        <li class="nav-item">
          <a class="btn btn-light btn-sm ms-3" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </li>

      </ul>
    </div>

  </div>
</nav>

<!-- =======================
       DASHBOARD CONTENT
======================= -->
<div class="container my-4">

  <h3 class="fw-bold mb-4">Welcome, Administrator</h3>

  <!-- ===== Stats Cards ===== -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card card-stats p-3 shadow-sm">
        <h4><?= $stats['applicants'] ?></h4>
        <p class="text-muted mb-0"><i class="bi bi-people"></i> Total Applicants</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-stats p-3 shadow-sm">
        <h4><?= $stats['pending'] ?></h4>
        <p class="text-muted mb-0"><i class="bi bi-hourglass-split"></i> Pending</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-stats p-3 shadow-sm">
        <h4><?= $stats['approved'] ?></h4>
        <p class="text-muted mb-0"><i class="bi bi-check-circle"></i> Approved</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-stats p-3 shadow-sm">
        <h4><?= $stats['notices'] ?></h4>
        <p class="text-muted mb-0"><i class="bi bi-megaphone"></i> Notices Posted</p>
      </div>
    </div>

  </div>

  <!-- ===== Shortcut Buttons ===== -->
  <div class="card p-3 shadow-sm mb-4">
    <h5 class="fw-bold mb-3">Quick Actions</h5>

    <div class="row g-3">

      <div class="col-md-3">
        <a href="admin_applicants.php" class="btn btn-primary w-100 shortcut-btn">
          <i class="bi bi-people"></i> Manage Applicants
        </a>
      </div>

      <div class="col-md-3">
        <a href="admin_add_notice.php" class="btn btn-warning w-100 shortcut-btn">
          <i class="bi bi-plus-circle"></i> Post New Notice
        </a>
      </div>

      <div class="col-md-3">
        <a href="admin_notices.php" class="btn btn-secondary w-100 shortcut-btn">
          <i class="bi bi-megaphone"></i> Notice Manager
        </a>
      </div>

      <div class="col-md-3">
        <a href="admin_settings.php" class="btn btn-dark w-100 shortcut-btn">
          <i class="bi bi-gear"></i> Settings
        </a>
      </div>

    </div>
  </div>


  <div class="row">

    <!-- Latest Applicants -->
    <div class="col-md-7">
      <div class="card p-3 shadow-sm mb-4">
        <h5 class="fw-bold mb-3">Latest Applicants</h5>

        <?php while($a = $latest_applicants->fetch_assoc()): ?>
          <div class="d-flex align-items-center border-bottom py-2">
            <strong><?= htmlspecialchars($a['fullname']) ?></strong>
            <span class="ms-auto text-muted"><?= htmlspecialchars($a['course']) ?></span>
            <a href="view_application.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-primary ms-3">View</a>
          </div>
        <?php endwhile; ?>

      </div>
    </div>

    <!-- Latest Notices -->
    <div class="col-md-5">
      <div class="card p-3 shadow-sm mb-4">
        <h5 class="fw-bold mb-3">Recent Notices</h5>

        <?php while($n = $latest_notices->fetch_assoc()): ?>
          <div class="border-bottom py-2">
            <a href="notice_view.php?id=<?= $n['id'] ?>" class="fw-bold">
              <?= htmlspecialchars($n['title']) ?>
            </a>
            <div class="text-muted small"><?= $n['created_at'] ?></div>
          </div>
        <?php endwhile; ?>

      </div>
    </div>

  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// admin_header.php
// expects session already started and admin logged in
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg" style="background:#b43b3b;">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="admin_dashboard.php">
      <strong>Abba Online Academic — Admin</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-white" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="admin_applicants.php">Applicants</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="admin_notices.php">Notices</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="admin_add_notice.php">Add Notice</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

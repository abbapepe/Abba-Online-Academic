<?php
include 'db_connect.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM notices WHERE id=? AND status='published'");
$stmt->bind_param("i", $id);
$stmt->execute();
$notice = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$notice) { 
    die("Notice not found."); 
}

$attachments = json_decode($notice['attachments_json'], true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($notice['title']) ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body {
      background: #f8f9fa;
    }
  </style>
</head>

<body>

<!-- ===========================
      REPLACED HEADER (OPTION A)
=========================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-college">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.html">
      <img src="assets/img/log.jpg" alt="logo" width="40" class="me-2">
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
        <li class="nav-item"><a class="nav-link active" href="notices.php">Notice</a></li>
        <li class="nav-item"><a class="nav-link" href="Student-portal.html">Student</a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- END HEADER -->


<div class="container my-5">

  <div class="card shadow-sm">
    <div class="card-body">

      <h2 class="fw-bold"><?= htmlspecialchars($notice['title']) ?></h2>

      <small class="text-muted d-block mb-3">
        <?= htmlspecialchars($notice['category']) ?> • 
        <?= htmlspecialchars($notice['created_at']) ?>
      </small>

      <div class="mt-3" style="white-space:pre-line;">
        <?= nl2br(htmlspecialchars($notice['content'])) ?>
      </div>

      <?php if ($attachments): ?>
        <h5 class="mt-4">Attachments</h5>
        <ul>
          <?php foreach ($attachments as $a): ?>
            <li>
              <a href="<?= htmlspecialchars($a['path']) ?>" target="_blank">
                <?= htmlspecialchars($a['name']) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <a href="notices.php" class="btn btn-secondary mt-4">Back to Notices</a>

    </div>
  </div>

</div>


<!-- ===========================
            FOOTER
=========================== -->
<footer class="bg-college text-white text-center py-4">
  <div class="container">

    <div class="mb-3">
      <a href="#" class="text-white me-3 fs-5"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-white me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
      <a href="#" class="text-white me-3 fs-5"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
    </div>

    <small>© <span id="year"></span> Abba Online Academic. All Rights Reserved.</small>
  </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("year").textContent = new Date().getFullYear();
</script>

</body>
</html>

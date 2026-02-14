<?php
include 'db_connect.php';

$perPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');

$where = "WHERE status='published'";
$params = [];
$types = "";

if ($category) { 
    $where .= " AND category = ?"; 
    $params[] = $category; 
    $types .= "s"; 
}
if ($search) { 
    $where .= " AND (title LIKE ? OR content LIKE ?)"; 
    $like = "%{$search}%";
    $params[] = $like; 
    $params[] = $like; 
    $types .= "ss"; 
}

// Count total
$count_sql = "SELECT COUNT(*) AS total FROM notices $where";
$stmt = $conn->prepare($count_sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Fetch records
$sql = "SELECT id,title,content,category,created_at FROM notices 
        $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($types) {
    $bind_types = $types . "ii";
    $bind_params = array_merge($params, [$perPage, $offset]);
    $stmt->bind_param($bind_types, ...$bind_params);
} else {
    $stmt->bind_param("ii", $perPage, $offset);
}

$stmt->execute();
$res = $stmt->get_result();

$categories = ['Admissions','Exams','General','Events','Internal Memo','News'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Notice Board - Abba Online Academic</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body { background: #f8f9fa; }
  </style>
</head>

<body>

<!-- ===========================
      PUBLIC HEADER (MATCHED)
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

        <li class="nav-item">
          <a class="nav-link" href="index.html">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="apply.html">Apply</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="check_status.html">Check Status</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" href="notices.php">Notice</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="Student-portal.html">Student</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
<!-- END HEADER -->


<!-- ===========================
         NOTICE LIST
=========================== -->
<div class="container my-5">
  
  <h3 class="fw-bold mb-4 text-primary">Notice Board</h3>

  <!-- Search + Filter -->
  <form class="row g-2 mb-4" method="get">
    <div class="col-md-4">
      <input name="search" class="form-control" placeholder="Search notices..."
        value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="col-md-3">
      <select name="category" class="form-select">
        <option value="">All categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= htmlspecialchars($c) ?>"
            <?= $category == $c ? 'selected' : '' ?>>
            <?= htmlspecialchars($c) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2">
      <button class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <!-- Notice Cards -->
  <?php while ($row = $res->fetch_assoc()): ?>
    <div class="card shadow-sm mb-3">
      <div class="card-body">

        <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']) ?></h5>

        <small class="text-muted">
          <?= htmlspecialchars($row['category']) ?> • <?= htmlspecialchars($row['created_at']) ?>
        </small>

        <p class="card-text mt-2">
          <?= nl2br(htmlspecialchars(substr($row['content'], 0, 300))) ?>...
        </p>

        <a href="notice_view.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
          Read More
        </a>

      </div>
    </div>
  <?php endwhile; ?>

  <!-- Pagination -->
  <?php
  $totalPages = max(1, ceil($total / $perPage));
  if ($totalPages > 1):
  ?>
  <nav>
    <ul class="pagination">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <li class="page-item <?= $p == $page ? 'active' : '' ?>">
          <a class="page-link" 
             href="?page=<?= $p ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>">
             <?= $p ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>

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

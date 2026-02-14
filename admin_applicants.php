<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* ---------- Inputs ---------- */
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

/* ---------- Base WHERE ---------- */
$where = "WHERE 1";
$params = [];
$types = "";

if (!empty($search)) {
    $where .= " AND (fullname LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ?)";
    $like = "%{$search}%";
    $params = array_merge($params, [$like, $like, $like, $like]);
    $types .= "ssss";
}
if (!empty($status_filter)) {
    $where .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

/* ---------- Count total ---------- */
$count_sql = "SELECT COUNT(*) AS total FROM applications $where";
$stmt = $conn->prepare($count_sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

/* ---------- Fetch page rows ---------- */
$sql = "SELECT id, fullname, email, phone, course, status, created_at 
        FROM applications $where 
        ORDER BY id DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($types) {
    $bind_types = $types . "ii";
    $bind_params = array_merge($params, [$limit, $offset]);
    $stmt->bind_param($bind_types, ...$bind_params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

/* ---------- Pagination ---------- */
$total_pages = max(1, ceil($total / $limit));

function qs(array $arr = []) {
    return http_build_query(array_merge($_GET, $arr));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicants List - Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    body { background:#f4f4f4; }
    .admin-header { background:#b43b3b; }
    .admin-header a { color:white !important; font-weight:500; }
    .table th { background:#b43b3b; color:white; }
    .badge-pending{ background:#ffc107; color:#000; }
    .badge-approved{ background:#28a745; }
    .badge-denied{ background:#dc3545; }
</style>
</head>

<body>

<!-- ADMIN HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark admin-header">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="admin_dashboard.php">
      <i class="bi bi-speedometer2"></i> Admin Dashboard
    </a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="admin_applicants.php">Applicants</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_notices.php">Notices</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_courses.php">Courses</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_payments.php">Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_settings.php">Settings</a></li>

        <li class="nav-item ms-3">
          <a class="btn btn-light btn-sm" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </li>

      </ul>
    </div>

  </div>
</nav>

<div class="container my-4">

    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-danger mb-0">Applicants List</h4>

            <a href="export.php?<?= qs() ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
        </div>

        <!-- Search + Filter -->
        <form method="GET" class="row g-2 mb-4">
          <div class="col-md-6">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                   class="form-control" placeholder="Search by name, email, phone, course">
          </div>

          <div class="col-md-3">
            <select name="status" class="form-select">
              <option value="">Filter by Status</option>
              <option value="pending" <?= $status_filter=='pending'?'selected':'' ?>>Pending</option>
              <option value="approved" <?= $status_filter=='approved'?'selected':'' ?>>Approved</option>
              <option value="denied" <?= $status_filter=='denied'?'selected':'' ?>>Denied</option>
            </select>
          </div>

          <div class="col-md-3 d-grid">
            <button class="btn" style="background:#b43b3b;color:white;">Apply Filter</button>
          </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>

                        <td>
                            <?php if ($row['status'] == 'approved'): ?>
                              <span class="badge badge-approved">Approved</span>
                            <?php elseif ($row['status'] == 'denied'): ?>
                              <span class="badge badge-denied">Denied</span>
                            <?php else: ?>
                              <span class="badge badge-pending">Pending</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($row['created_at']) ?></td>

                        <td>
                            <a href="view_application.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                            <a href="edit_application.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_application.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Delete this record?');"
                               class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
            <?php for ($p=1; $p <= $total_pages; $p++): ?>
              <li class="page-item <?= ($p==$page?'active':'') ?>">
                <a class="page-link" href="?<?= qs(['page'=>$p]) ?>"><?= $p ?></a>
              </li>
            <?php endfor; ?>
            </ul>
        </nav>

        <?php else: ?>
            <p class="text-center text-muted">No applicants found.</p>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

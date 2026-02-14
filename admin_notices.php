<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$perPage = 20;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// filter by category or status if provided
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';
$where = "WHERE 1";
$params = [];
$types = "";

if ($category) { $where .= " AND category = ?"; $params[] = $category; $types .= "s"; }
if ($status) { $where .= " AND status = ?"; $params[] = $status; $types .= "s"; }

// count total
$count_sql = "SELECT COUNT(*) AS total FROM notices $where";
$stmt = $conn->prepare($count_sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$sql = "SELECT * FROM notices $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
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

$categories = ['Admissions','Exams','News','General','Events','Internal Memo'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Notices</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin_header.php'; ?>

<div class="container my-4">
  <h3>Manage Notices</h3>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <select name="category" class="form-select">
        <option value="">All categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= htmlspecialchars($c) ?>" <?= $category==$c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">All status</option>
        <option value="published" <?= $status=='published' ? 'selected' : '' ?>>Published</option>
        <option value="draft" <?= $status=='draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary">Filter</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr><th>ID</th><th>Title</th><th>Category</th><th>Status</th><th>Featured</th><th>Date</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= $row['is_featured'] ? 'Yes' : 'No' ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <a class="btn btn-sm btn-primary" href="notice_view.php?id=<?= $row['id'] ?>" target="_blank">View</a>
          <a class="btn btn-sm btn-warning" href="admin_edit_notice.php?id=<?= $row['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="admin_delete_notice.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <?php
  $totalPages = max(1, ceil($total / $perPage));
  if ($totalPages > 1):
  ?>
  <nav>
    <ul class="pagination">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <li class="page-item <?= $p==$page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $p ?>&category=<?= urlencode($category) ?>&status=<?= urlencode($status) ?>"><?= $p ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>

</div>
</body>
</html>

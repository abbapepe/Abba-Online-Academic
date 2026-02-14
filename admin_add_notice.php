<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$message = '';
$allowed_ext = ['pdf','doc','docx','jpg','jpeg','png','txt'];
$max_size = 1 * 1024 * 1024; // 1MB
$upload_dir = 'notice_uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$categories = ['Admissions','Exams','News','General','Events','Internal Memo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = in_array($_POST['category'] ?? 'General', $categories) ? $_POST['category'] : 'General';
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    $attachments = [];

    // handle multiple file inputs (input name="attachments[]" multiple)
    if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
        foreach ($_FILES['attachments']['name'] as $i => $name) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK && $name) {
                $tmp = $_FILES['attachments']['tmp_name'][$i];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $size = $_FILES['attachments']['size'][$i];

                if (!in_array($ext, $allowed_ext)) continue;
                if ($size > $max_size) continue;

                $safe = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $target = $upload_dir . $safe;
                if (move_uploaded_file($tmp, $target)) {
                    $attachments[] = ['path' => $target, 'name' => $name];
                }
            }
        }
    }

    $attachments_json = !empty($attachments) ? json_encode($attachments) : null;

    $stmt = $conn->prepare("INSERT INTO notices (title, content, category, attachments_json, status, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $content, $category, $attachments_json, $status, $is_featured);
    if ($stmt->execute()) {
        $message = "Notice created successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Notice - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin_header.php'; ?>

<div class="container my-4">
  <h3>Add New Notice</h3>
  <?php if ($message): ?><div class="alert alert-info"><?= htmlspecialchars($message) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input name="title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="6" required></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select">
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="published">Published</option>
          <option value="draft">Draft</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label d-block">Featured</label>
        <input type="checkbox" name="is_featured" value="1"> Mark as featured
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Attachments (max 1MB each)</label>
      <input type="file" name="attachments[]" class="form-control" multiple>
      <small class="text-muted">Allowed: pdf, doc, docx, jpg, jpeg, png, txt</small>
    </div>

    <button class="btn" style="background:#b43b3b;color:#fff;">Save Notice</button>
  </form>
</div>
</body>
</html>

<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM notices WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$notice = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$notice) { die("Notice not found."); }

$categories = ['Admissions','Exams','News','General','Events','Internal Memo'];

$upload_dir = 'notice_uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = in_array($_POST['category'],$categories) ? $_POST['category'] : 'General';
    $status = ($_POST['status']=='published') ? 'published' : 'draft';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // existing attachments
    $existing = json_decode($notice['attachments_json'], true) ?: [];

    // handle new uploads and append
    $allowed_ext = ['pdf','doc','docx','jpg','jpeg','png','txt'];
    $max_size = 1*1024*1024;

    if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
        foreach ($_FILES['attachments']['name'] as $i => $name) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK && $name) {
                $tmp = $_FILES['attachments']['tmp_name'][$i];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $size = $_FILES['attachments']['size'][$i];
                if (!in_array($ext,$allowed_ext)) continue;
                if ($size > $max_size) continue;
                $safe = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $target = $upload_dir . $safe;
                if (move_uploaded_file($tmp,$target)) $existing[] = ['path'=>$target,'name'=>$name];
            }
        }
    }

    // optionally remove attachments (if admin checked)
    if (!empty($_POST['remove_files'])) {
        foreach ($_POST['remove_files'] as $p) {
            // remove from existing where path matches
            foreach ($existing as $k => $f) {
                if ($f['path'] === $p) {
                    if (file_exists($p)) @unlink($p);
                    unset($existing[$k]);
                }
            }
        }
        $existing = array_values($existing);
    }

    $attachments_json = !empty($existing) ? json_encode($existing) : null;

    $upd = $conn->prepare("UPDATE notices SET title=?, content=?, category=?, attachments_json=?, status=?, is_featured=? WHERE id=?");
    $upd->bind_param("ssssiii", $title, $content, $category, $attachments_json, $status, $is_featured, $id);
    if ($upd->execute()) $message = "Updated successfully.";
    else $message = "Error: " . $upd->error;
    $upd->close();

    // refresh notice data
    $stmt = $conn->prepare("SELECT * FROM notices WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $notice = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Notice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container my-4">
  <h3>Edit Notice</h3>
  <?php if ($message): ?><div class="alert alert-info"><?= htmlspecialchars($message) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?= htmlspecialchars($notice['title']) ?>" required></div>
    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="6"><?= htmlspecialchars($notice['content']) ?></textarea></div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label>Category</label>
        <select name="category" class="form-select">
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $notice['category']==$c ? 'selected':'' ?>><?= htmlspecialchars($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="published" <?= $notice['status']=='published' ? 'selected' : '' ?>>Published</option>
          <option value="draft" <?= $notice['status']=='draft' ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="d-block">Featured</label>
        <input type="checkbox" name="is_featured" value="1" <?= $notice['is_featured'] ? 'checked' : '' ?>> Featured
      </div>
    </div>

    <div class="mb-3">
      <label>Current Attachments</label>
      <ul>
        <?php
        $existing = json_decode($notice['attachments_json'], true) ?: [];
        foreach ($existing as $f): ?>
          <li>
            <a href="<?= htmlspecialchars($f['path']) ?>" target="_blank"><?= htmlspecialchars($f['name']) ?></a>
            <label class="ms-2"><input type="checkbox" name="remove_files[]" value="<?= htmlspecialchars($f['path']) ?>"> Remove</label>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="mb-3">
      <label>Upload Additional Attachments</label>
      <input type="file" name="attachments[]" multiple class="form-control">
      <small class="form-text text-muted">Allowed: pdf, doc, docx, jpg, jpeg, png, txt (max 1MB each)</small>
    </div>

    <button class="btn" style="background:#b43b3b;color:#fff;">Save Changes</button>
  </form>
</div>
</body>
</html>

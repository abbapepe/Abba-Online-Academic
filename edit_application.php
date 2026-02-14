<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

/* ✅ Fetch existing data */
$stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$applicant = $stmt->get_result()->fetch_assoc();

if (!$applicant) {
    echo "<h4 class='text-center text-danger mt-5'>Application not found!</h4>";
    exit();
}

$uploads = !empty($applicant['uploads_json']) ? json_decode($applicant['uploads_json'], true) : [];
$passport = $uploads['passport'] ?? '';
$olevelFile = $uploads['olevel'] ?? '';
$birthCert = $uploads['birth_cert'] ?? '';
$primaryCert = $uploads['primary_cert'] ?? '';

/* ✅ Handle Form Submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $lga = $_POST['lga'];
    $address = $_POST['address'];
    $school = $_POST['school'];
    $qualification = $_POST['qualification'];
    $course = $_POST['course'];

    // ✅ File uploads directory
    $upload_dir = "uploads/";

    // ✅ Check & move uploaded files (only if provided)
    $file_fields = [
        'passport' => $passport,
        'olevel' => $olevelFile,
        'birth_cert' => $birthCert,
        'primary_cert' => $primaryCert
    ];

    foreach ($file_fields as $key => $old_path) {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES[$key]['tmp_name'];
            $filename = time() . "_" . basename($_FILES[$key]['name']);
            $target = $upload_dir . $filename;
            move_uploaded_file($tmp_name, $target);
            $file_fields[$key] = $target;
        }
    }

    // ✅ Update uploads JSON
    $uploads_json = json_encode($file_fields);

    // ✅ Update DB record
    $stmt = $conn->prepare("UPDATE applications SET fullname=?, email=?, phone=?, gender=?, state=?, lga=?, address=?, school=?, qualification=?, course=?, uploads_json=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $fullname, $email, $phone, $gender, $state, $lga, $address, $school, $qualification, $course, $uploads_json, $id);
    $stmt->execute();

    // Redirect back to the view page
    header("Location: view_application.php?id=" . $id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Application - Abba Online Academic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    nav { background-color: #b43b3b; color: #fff; padding: 10px 20px; }
    .card { margin-top: 30px; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
    label { font-weight: 500; }
  </style>
</head>
<body>

<nav class="d-flex justify-content-between align-items-center">
  <div><h4>Abba Online Academic Admin Panel</h4></div>
  <div><a href="admin_dashboard.php" class="btn btn-light btn-sm">Back to Dashboard</a></div>
</nav>

<div class="container">
  <div class="card">
    <h4 class="text-danger mb-3">Edit Applicant Information</h4>
    <form method="POST" enctype="multipart/form-data">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Full Name</label>
          <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($applicant['fullname']); ?>" required>
        </div>
        <div class="col-md-6">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($applicant['email']); ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Phone</label>
          <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($applicant['phone']); ?>" required>
        </div>
        <div class="col-md-3">
          <label>Gender</label>
          <select name="gender" class="form-select" required>
            <option value="Male" <?php if ($applicant['gender']=="Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if ($applicant['gender']=="Female") echo "selected"; ?>>Female</option>
          </select>
        </div>
        <div class="col-md-3">
          <label>State</label>
          <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($applicant['state']); ?>">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label>LGA</label>
          <input type="text" name="lga" class="form-control" value="<?php echo htmlspecialchars($applicant['lga']); ?>">
        </div>
        <div class="col-md-8">
          <label>Address</label>
          <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($applicant['address']); ?>">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Previous School</label>
          <input type="text" name="school" class="form-control" value="<?php echo htmlspecialchars($applicant['school']); ?>">
        </div>
        <div class="col-md-3">
          <label>Qualification</label>
          <input type="text" name="qualification" class="form-control" value="<?php echo htmlspecialchars($applicant['qualification']); ?>">
        </div>
        <div class="col-md-3">
          <label>Course</label>
          <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($applicant['course']); ?>">
        </div>
      </div>

      <hr>
      <h5 class="text-danger mt-3">Replace Uploaded Documents (Optional)</h5>

      <div class="mb-2">
        <label>Passport Photo</label><br>
        <?php if ($passport): ?><a href="<?php echo htmlspecialchars($passport); ?>" target="_blank">View current</a><?php endif; ?>
        <input type="file" name="passport" class="form-control mt-2" accept=".jpg,.jpeg,.png">
      </div>

      <div class="mb-2">
        <label>O'Level Document</label><br>
        <?php if ($olevelFile): ?><a href="<?php echo htmlspecialchars($olevelFile); ?>" target="_blank">View current</a><?php endif; ?>
        <input type="file" name="olevel" class="form-control mt-2" accept=".jpg,.jpeg,.png,.pdf">
      </div>

      <div class="mb-2">
        <label>Birth Certificate</label><br>
        <?php if ($birthCert): ?><a href="<?php echo htmlspecialchars($birthCert); ?>" target="_blank">View current</a><?php endif; ?>
        <input type="file" name="birth_cert" class="form-control mt-2" accept=".jpg,.jpeg,.png,.pdf">
      </div>

      <div class="mb-2">
        <label>Primary School Certificate</label><br>
        <?php if ($primaryCert): ?><a href="<?php echo htmlspecialchars($primaryCert); ?>" target="_blank">View current</a><?php endif; ?>
        <input type="file" name="primary_cert" class="form-control mt-2" accept=".jpg,.jpeg,.png,.pdf">
      </div>

      <div class="text-end mt-4">
        <button type="submit" class="btn" style="background-color:#b43b3b; color:#fff;">Save Changes</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No application selected.");
}

$id = intval($_GET['id']);

/* =========================
   FETCH APPLICATION DETAILS
   ========================= */
$stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app) {
    die("Application not found.");
}

/* Decode JSON Data */
$olevel_json = json_decode($app['olevel_json'], true);
$uploads_json = json_decode($app['uploads_json'], true);

/* ===============================
   HANDLE APPROVE / DENY ACTION
   =============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $new_status = ($_POST['action'] === 'approve') ? 'approved' : 'denied';

    // Update status in database
    $update = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    $update->execute();
    $update->close();

    // Get applicant name + email
    $fullname = $app['fullname'];
    $email = $app['email'];

    /* SEND EMAIL */
    if (!empty($email)) {

        if ($new_status === "approved") {
            $subject = "Admission Offer - Abba Online Academic";
            $message = "
                <p>Dear <strong>{$fullname}</strong>,</p>
                <p>Congratulations! You have been offered admission to Abba Online Academic.</p>
                <p>Please follow further instructions provided by the Admissions Office.</p>
                <p>Regards,<br>Abba Online Academic Admissions</p>
            ";
        } else {
            $subject = "Admission Update - Abba Online Academic";
            $message = "
                <p>Dear <strong>{$fullname}</strong>,</p>
                <p>We regret to inform you that your application was not successful this year.</p>
                <p>Please try again next year.</p>
                <p>Regards,<br>Abba Online Academic Admissions</p>
            ";
        }

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Abba Online Academic <admissions@yourdomain.com>\r\n";

        @mail($email, $subject, $message, $headers);
    }

    // Refresh page
    header("Location: view_application.php?id=" . $id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details - Abba Olnine Academic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        nav { background-color: #b43b3b; padding: 10px 20px; color: white; }
        .card { margin-top: 25px; border-radius: 10px; }
        .section-title { background: #b43b3b; color: white; padding: 8px; border-radius: 5px; }
        .img-preview { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd; }
        .badge-pending { background-color: #ffc107; }
        .badge-approved { background-color: #28a745; }
        .badge-denied { background-color: #dc3545; }
    </style>
</head>
<body>

<!-- HEADER -->
<nav>
    <h4>Abba Online Academic - Application Details</h4>
</nav>

<div class="container">

<div class="card p-4">

    <h4 class="text-danger">Applicant Information</h4>
    <hr>

    <!-- Status Display -->
    <p>
        <strong>Status:</strong>
        <?php if ($app['status'] === 'approved'): ?>
            <span class="badge badge-approved">Approved</span>
        <?php elseif ($app['status'] === 'denied'): ?>
            <span class="badge badge-denied">Denied</span>
        <?php else: ?>
            <span class="badge badge-pending">Pending</span>
        <?php endif; ?>
    </p>

    <!-- Approve / Deny Buttons -->
    <form method="POST" class="mb-3">
        <button name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
        <button name="action" value="deny" class="btn btn-danger btn-sm">Deny</button>
    </form>

    <a href="admin_dashboard.php" class="btn btn-dark btn-sm mb-3">← Back to Dashboard</a>

    <h5 class="section-title">Personal Details</h5>
    <p><strong>Full Name:</strong> <?= htmlspecialchars($app['fullname']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($app['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($app['phone']) ?></p>
    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($app['dob']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($app['gender']) ?></p>
    <p><strong>State:</strong> <?= htmlspecialchars($app['state']) ?></p>
    <p><strong>LGA:</strong> <?= htmlspecialchars($app['lga']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($app['address']) ?></p>

    <h5 class="section-title mt-4">Academic Details</h5>
    <p><strong>Last School Attended:</strong> <?= htmlspecialchars($app['school']) ?></p>
    <p><strong>Qualification:</strong> <?= htmlspecialchars($app['qualification']) ?></p>
    <p><strong>Course Applied:</strong> <?= htmlspecialchars($app['course']) ?></p>

    <h5 class="section-title mt-4">O'Level Results</h5>

    <?php foreach ($olevel_json as $sitting => $subjects): ?>
        <h6 class="mt-3"><strong><?= ucfirst(str_replace("_", " ", $sitting)) ?></strong></h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($subjects as $subject => $grade): ?>
                <?php if (is_array($grade)): ?>
                    <!-- For "Others" subject -->
                    <tr>
                        <td><?= htmlspecialchars($grade['subject']) ?></td>
                        <td><?= htmlspecialchars($grade['grade']) ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?= htmlspecialchars($subject) ?></td>
                        <td><?= htmlspecialchars($grade) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>


    <h5 class="section-title mt-4">Credentials</h5>

    <div class="row">

        <!-- Passport -->
        <?php if (!empty($uploads_json['passport'])): ?>
        <div class="col-md-3 text-center">
            <p><strong>Passport</strong></p>
            <img src="<?= $uploads_json['passport'] ?>" class="img-preview">
        </div>
        <?php endif; ?>

        <!-- O'Level -->
        <?php if (!empty($uploads_json['olevel'])): ?>
        <div class="col-md-3 text-center">
            <p><strong>O'Level Result</strong></p>
            <img src="<?= $uploads_json['olevel'] ?>" class="img-preview">
        </div>
        <?php endif; ?>

        <!-- Birth -->
        <?php if (!empty($uploads_json['birth_cert'])): ?>
        <div class="col-md-3 text-center">
            <p><strong>Birth Certificate</strong></p>
            <img src="<?= $uploads_json['birth_cert'] ?>" class="img-preview">
        </div>
        <?php endif; ?>

        <!-- Primary -->
        <?php if (!empty($uploads_json['primary_cert'])): ?>
        <div class="col-md-3 text-center">
            <p><strong>Primary School Cert</strong></p>
            <img src="<?= $uploads_json['primary_cert'] ?>" class="img-preview">
        </div>
        <?php endif; ?>

    </div>

</div>
</div>

</body>
</html>

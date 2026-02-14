<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: admin_notices.php"); exit(); }

// remove attachments files
$stmt = $conn->prepare("SELECT attachments_json FROM notices WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($row && !empty($row['attachments_json'])) {
    $files = json_decode($row['attachments_json'], true);
    if (is_array($files)) {
        foreach ($files as $f) {
            if (!empty($f['path']) && file_exists($f['path'])) @unlink($f['path']);
        }
    }
}

$del = $conn->prepare("DELETE FROM notices WHERE id=?");
$del->bind_param("i",$id);
$del->execute();
$del->close();

header("Location: admin_notices.php");
exit();

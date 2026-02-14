<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['admin'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Forbidden";
    exit();
}

/* Get same filters as dashboard */
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$where = "WHERE 1";
$params = [];
$types = "";

if (!empty($search)) {
    $where .= " AND (fullname LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ?)";
    $like = "%{$search}%";
    $params = array_merge($params, [$like,$like,$like,$like]);
    $types .= "ssss";
}
if (!empty($status_filter)) {
    $where .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql = "SELECT id, fullname, email, phone, state, lga, course, status, created_at FROM applications $where ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

/* CSV headers */
$filename = "applications_export_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

/* Output */
$out = fopen('php://output', 'w');
fputcsv($out, ['ID','Full Name','Email','Phone','State','LGA','Course','Status','Created At']);

while ($row = $result->fetch_assoc()) {
    fputcsv($out, [
        $row['id'],
        $row['fullname'],
        $row['email'],
        $row['phone'],
        $row['state'],
        $row['lga'],
        $row['course'],
        $row['status'],
        $row['created_at']
    ]);
}
fclose($out);
exit();

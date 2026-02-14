<?php
include 'db_connect.php';
header('Content-Type: application/json');
$stmt = $conn->prepare("SELECT id, title, content, created_at FROM notices WHERE status='published' ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$n = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$n) { echo json_encode([]); exit(); }
$excerpt = mb_substr(strip_tags($n['content']),0,120).'...';
echo json_encode([
  'id' => $n['id'],
  'title' => $n['title'],
  'excerpt' => $excerpt,
  'url' => 'notice_view.php?id='.$n['id'],
  'created_at' => $n['created_at']
]);

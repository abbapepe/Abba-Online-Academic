<?php
include 'db_connect.php';
header('Content-Type: application/json');
$res = $conn->query("SELECT id,title,created_at FROM notices WHERE status='published' ORDER BY created_at DESC LIMIT 5");
$out = [];
while ($r = $res->fetch_assoc()){
  $out[] = ['id'=>$r['id'],'title'=>$r['title'],'created_at'=>$r['created_at'],'url'=>'notice_view.php?id='.$r['id']];
}
echo json_encode($out);

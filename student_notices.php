<?php
include "db.php";

$res = $conn->query("SELECT * FROM notices WHERE status='published' ORDER BY id DESC");

while($n = $res->fetch_assoc()){

echo "<h5>".$n['title']."</h5>";
echo "<p>".$n['content']."</p><hr>";

}
?>
<a href="dashboard.php">Back</a>
<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
    $tmp = $_FILES['csv']['tmp_name'];
    if(($h = fopen($tmp,'r')) !== false) {
        $header = fgetcsv($h);
        while(($row = fgetcsv($h)) !== false) {
            // map by header
            $assoc = [];
            foreach($header as $i=>$col) $assoc[$col] = $row[$i] ?? '';
            // push to firebase
            firebase_request('POST', 'users', $assoc);
        }
        fclose($h);
    }
    header('Location: users.php');
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>استيراد CSV</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="header"><div>استيراد بيانات</div><div><a href="users.php" style="color:white">رجوع</a></div></div>
<div class="container"><div class="card">
<form method="post" enctype="multipart/form-data">
<input type="file" name="csv" accept=".csv" required>
<div style="margin-top:12px"><button class="btn" type="submit">استيراد</button></div>
</form>
</div></div></body></html>

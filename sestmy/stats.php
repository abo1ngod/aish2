<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';
$all = firebase_request('GET', 'users') ?: [];
$counts = ['total'=>0,'students'=>0,'teachers'=>0,'admins'=>0];
foreach($all as $id=>$u) {
    $counts['total']++;
    $role = $u['role'] ?? 'student';
    if($role=='student') $counts['students']++;
    if($role=='teacher') $counts['teachers']++;
    if($role=='admin') $counts['admins']++;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>الإحصائيات</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="header"><div>الإحصائيات</div><div><a href="dashboard.php" style="color:white">لوحة التحكم</a></div></div>
<div class="container">
  <div class="grid">
    <div class="col-8"><div class="card">
      <h3>إحصائيات عامة</h3>
      <ul>
        <li>إجمالي المستخدمين: <?=$counts['total']?></li>
        <li>عدد الطلاب: <?=$counts['students']?></li>
        <li>عدد المعلمين: <?=$counts['teachers']?></li>
        <li>عدد المدراء: <?=$counts['admins']?></li>
      </ul>
    </div></div>
    <div class="col-4"><div class="card">
      <h4>تصدير / استيراد</h4>
      <div style="margin-top:12px">
        <a class="btn" href="export.php">تصدير CSV</a>
        <a class="btn" href="import.php">استيراد CSV</a>
      </div>
    </div></div>
  </div>
</div>
</body></html>

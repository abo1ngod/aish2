<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';

// جلب المستخدمين من Firebase
$all = firebase_request('GET', 'users') ?: [];
// Firebase returns object of objects; convert to array with id
$rows = [];
if(is_array($all)) {
    foreach($all as $id => $item) {
        $item['id'] = $id;
        $rows[] = $item;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>المستخدمون</title>
<link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="header"><div>قائمة المستخدمين</div><div><a href="dashboard.php" style="color:white">لوحة التحكم</a></div></div>
<div class="container">
  <div class="card">
    <h3>المستخدمون</h3>
    <table class="table">
      <thead><tr><th>الاسم</th><th>النوع</th><th>التخصص / الفصل</th><th>صورة</th><th>إجراءات</th></tr></thead>
      <tbody>
<?php foreach($rows as $r): ?>
<tr>
  <td><?=htmlspecialchars($r['name'] ?? '-')?></td>
  <td><?=htmlspecialchars($r['role'] ?? '-')?></td>
  <td><?=htmlspecialchars($r['specialty'] ?? ($r['class'] ?? '-'))?></td>
  <td><?php if(!empty($r['photo'])): ?><img src="uploads/<?=htmlspecialchars($r['photo'])?>" class="upload-preview"><?php endif; ?></td>
  <td>
    <a class="btn" href="user_edit.php?id=<?=urlencode($r['id'])?>">تعديل</a>
    <a class="btn" href="delete_user.php?id=<?=urlencode($r['id'])?>" style="background:#ef4444">حذف</a>
  </td>
</tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body></html>

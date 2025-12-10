<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>لوحة التحكم</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="header"><div>نظام المدرسة</div><div>مرحبًا، <?=htmlspecialchars($_SESSION['user']['username'])?> | <a href="logout.php" style="color:white">خروج</a></div></div>
<div class="container">
  <div class="grid">
    <div class="col-8">
      <div class="card">
        <h3>قائمة الطلاب والمعلمين</h3>
        <p class="small">روابط سريعة لإدارة وإضافة وتصدير البيانات.</p>
        <div style="margin-top:12px">
          <a class="btn" href="users.php">إدارة المستخدمين</a>
          <a class="btn" href="user_edit.php">إضافة مستخدم</a>
          <a class="btn" href="export.php">تصدير CSV</a>
          <a class="btn" href="stats.php">الإحصائيات</a>
        </div>
      </div>
      <div class="card" style="margin-top:16px">
        <h3>ملاحظات</h3>
        <ul>
          <li class="small">تحكم بالصلاحيات من خلال حقل role لكل مستخدم (admin / teacher / student).</li>
          <li class="small">الصور تحفظ في مجلد uploads/ ويتم تخزين اسم الملف في Firebase.</li>
        </ul>
      </div>
    </div>
    <div class="col-4">
      <div class="card">
        <h4>إحصائيات سريعة</h4>
        <div id="quick-stats" class="small">جلب البيانات...</div>
      </div>
    </div>
  </div>
</div>
<script>
// يمكن استدعاء API لجلب إحصائيات صغيرة أو استخدام Firebase JS SDK
document.getElementById('quick-stats').innerText = 'يمكنك توصيل الإحصائيات عبر stats.php';
</script>
</body>
</html>

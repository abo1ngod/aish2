<?php
session_start();
if(isset($_SESSION['user'])) header('Location: dashboard.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>تسجيل دخول - نظام المدرسة</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="header"><div style="font-weight:700">نظام المدرسة</div></div>
<div class="container">
  <div class="card" style="max-width:480px;margin:0 auto">
    <h2>تسجيل الدخول</h2>
    <form method="post" action="auth.php">
      <div class="form-row"><input class="input" name="username" placeholder="اسم المستخدم" required></div>
      <div class="form-row"><input class="input" type="password" name="password" placeholder="كلمة المرور" required></div>
      <div class="form-row"><button class="btn" type="submit">دخول</button></div>
      <div class="small">ملاحظة: هذا مثال مبسط. صِلْه بحسابات حقيقية أو Firebase Auth للانتاج.</div>
    </form>
  </div>
</div>
</body>
</html>

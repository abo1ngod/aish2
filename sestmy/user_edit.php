<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';
$id = $_GET['id'] ?? null;
$data = null;
if($id) {
    $data = firebase_request('GET', 'users/'.$id);
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>إضافة/تعديل مستخدم</title>
<link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="header"><div>إضافة/تعديل مستخدم</div><div><a href="users.php" style="color:white">رجوع</a></div></div>
<div class="container">
  <div class="card" style="max-width:780px;margin:0 auto">
    <form action="save_user.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">
      <div class="form-row"><input class="input" name="name" placeholder="الاسم" value="<?=htmlspecialchars($data['name'] ?? '')?>" required></div>
      <div class="form-row"><select name="role" class="input">
        <option value="student" <?=(!isset($data['role']) || $data['role']=='student')?'selected':''?>>طالب</option>
        <option value="teacher" <?=isset($data['role']) && $data['role']=='teacher'?'selected':''?>>معلم</option>
        <option value="admin" <?=isset($data['role']) && $data['role']=='admin'?'selected':''?>>مدير</option>
      </select></div>
      <div class="form-row"><input class="input" name="specialty" placeholder="التخصص أو الفصل" value="<?=htmlspecialchars($data['specialty'] ?? '')?>"></div>
      <div class="form-row"><input type="file" name="photo" accept="image/*"></div>
      <?php if(!empty($data['photo'])): ?><div class="form-row"><img src="uploads/<?=htmlspecialchars($data['photo'])?>" class="upload-preview"></div><?php endif; ?>
      <div class="form-row"><button class="btn" type="submit">حفظ</button></div>
    </form>
  </div>
</div>
</body></html>

<?php
session_start();
require_once 'firebase_helper.php';

// مبسط: نسمح بدخول أي اسم/كلمة مرور ثابتة للمثال (admin/admin).
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if($username === 'admin' && $password === 'admin') {
    $_SESSION['user'] = ['username'=>'admin','role'=>'admin'];
    header('Location: dashboard.php');
    exit;
} else {
    // يمكن التحقق من حسابات مخزنة في Firebase تحت /users
    header('Location: index.php?err=1');
    exit;
}

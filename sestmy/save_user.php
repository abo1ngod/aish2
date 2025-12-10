<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$role = $_POST['role'] ?? 'student';
$specialty = $_POST['specialty'] ?? '';

$photo_filename = null;
// handle upload
if(!empty($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $uploaddir = __DIR__ . '/uploads';
    if(!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photo_filename = uniqid('img_') . '.' . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], $uploaddir . '/' . $photo_filename);
}

// prepare payload
$payload = [
    'name' => $name,
    'role' => $role,
    'specialty' => $specialty,
];
if($photo_filename) $payload['photo'] = $photo_filename;

if($id) {
    // update
    $res = firebase_request('PATCH', 'users/'.$id, $payload);
} else {
    // create new (POST)
    $res = firebase_request('POST', 'users', $payload);
}
header('Location: users.php');
exit;

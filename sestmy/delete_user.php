<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';
$id = $_GET['id'] ?? null;
if($id) {
    // fetch to remove photo
    $item = firebase_request('GET', 'users/'.$id);
    if(!empty($item['photo'])) {
        $f = __DIR__.'/uploads/'.$item['photo'];
        if(file_exists($f)) @unlink($f);
    }
    firebase_request('DELETE', 'users/'.$id);
}
header('Location: users.php');
exit;

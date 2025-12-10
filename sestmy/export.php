<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
require_once 'firebase_helper.php';

$all = firebase_request('GET', 'users') ?: [];
$rows = [];
foreach($all as $id => $item) {
    $rows[] = [
        'id'=>$id,
        'name'=>$item['name'] ?? '',
        'role'=>$item['role'] ?? '',
        'specialty'=>$item['specialty'] ?? '',
        'photo'=>$item['photo'] ?? ''
    ];
}

// generate CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=export_users.csv');
$out = fopen('php://output','w');
fputcsv($out, array('id','name','role','specialty','photo'));
foreach($rows as $r) fputcsv($out, $r);
fclose($out);
exit;

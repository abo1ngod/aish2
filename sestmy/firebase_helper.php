<?php
// Simple PHP helper to interact with Firebase Realtime Database via REST API.
// It uses the databaseURL from user and performs GET/POST/PUT/DELETE on nodes.
// WARNING: For production, secure your Firebase DB with proper rules and auth.

define('FIREBASE_DB_URL', 'https://studentsystem-c1ab1-default-rtdb.firebaseio.com'); // from user's config

function firebase_request($method, $path, $data = null) {
    $url = rtrim(FIREBASE_DB_URL, '/') . '/' . ltrim($path, '/') . '.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data !== null) {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    if(curl_errno($ch)) {
        $err = curl_error($ch);
        curl_close($ch);
        return array('error' => $err);
    }
    curl_close($ch);
    $decoded = json_decode($resp, true);
    return $decoded;
}

<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Only POST method allowed"]);
    http_response_code(405); // This is what causes the error you're seeing
    exit;
}

$url = $_GET['url'] ?? null;

if (!$url) {
    echo json_encode(["error" => "Missing URL"]);
    exit;
}

// Prepare POST data
$postFields = [];

foreach ($_POST as $key => $value) {
    $postFields[$key] = $value;
}

if (!empty($_FILES)) {
    foreach ($_FILES as $key => $file) {
        if (is_uploaded_file($file['tmp_name'])) {
            $postFields[$key] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        }
    }
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if (empty($response)) {
    echo json_encode(["error" => "Empty response from API"]);
    exit;
}

echo $response;

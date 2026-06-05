<?php
$url =$_GET['url'];
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // handle SSL
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Validate that it's JSON
if (empty($response)) {
    echo json_encode(["error" => "Empty response from API"]);
    exit;
}

// Set valid JSON response
echo $response;
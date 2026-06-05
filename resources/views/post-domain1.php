<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    $postFields = [];

    if (isset($_FILES['excel_file'])) {
        $file = $_FILES['excel_file'];
        $postFields['excel_file'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    // 👇 Add these lines to fix SSL error (for testing)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    echo $response;
}

<?php
include('../../config.php');
header("Content-Type: application/json");
date_default_timezone_set("Asia/Karachi");


// Absolute and public upload paths
$uploadDir = realpath('C:/xampp/htdocs/poralflow2/portalflowapis/uploads') . '/';
$uploadUrl = 'https://o2c.flowpetroleum.com.pk:9144/portalflowapis/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Function to handle file upload
function saveUploadedFile($field, $uploadDir, $uploadUrl)
{
    if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . '_' . basename($_FILES[$field]['name']);
        $fullPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $fullPath)) {
            return $uploadUrl . $filename;
        }
    }
    return '';
}

// Get POST or FILE data
$work_order_id = $_POST['work_order_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$message = $_POST['message'] ?? '';
$type = $_POST['type'] ?? 'text';
$pri = $_POST['privilege'];

if (!$work_order_id || !$user_id) {
    echo json_encode(["status" => false, "message" => "Missing required fields."]);
    exit;
}

// Fetch role-based user IDs
$query = "
    SELECT d.id, c.created_by AS dealer_id, d.tm AS tm_id, d.asm AS asm_id, d.zm AS zm_id
    FROM complaint c
    JOIN dealers d ON c.created_by = d.id
    WHERE c.id = '$work_order_id'
    LIMIT 1
";


$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(["status" => false, "message" => "Work order not found."]);
    exit;
}

$ids = mysqli_fetch_assoc($result);

// Handle file upload
$filePath = '';
if ($type === 'file') {
    $filePath = saveUploadedFile("chat_file", $uploadDir, $uploadUrl);
    if ($filePath === '') {
        echo json_encode(["status" => false, "message" => "File upload failed."]);
        exit;
    }
}

// Insert message directly
$insert = "
    INSERT INTO work_order_chat 
    (work_order_id, user_id, dealer_id, eng_id, tm_id, asm_id, zm_id, message, file_path, type, created_at, pivilege)
    VALUES (
        '$work_order_id',
        '$user_id',
        '{$ids['dealer_id']}',
        '',
        '{$ids['tm_id']}',
        '{$ids['asm_id']}',
        '{$ids['zm_id']}',
        '" . mysqli_real_escape_string($db, $message) . "',
        '" . mysqli_real_escape_string($db, $filePath) . "',
        '$type',
        NOW(),
        '$pri'
    )
";

if (mysqli_query($db, $insert)) {
    echo json_encode(["status" => true, "message" => "Message sent successfully."]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to send message."]);
}

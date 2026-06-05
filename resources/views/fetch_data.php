
<?php
include('../config.php');
header("Content-Type: application/json");

$current_user_id = $_GET['user_id'] ?? null;
$work_order_id = $_GET['work_order_id'] ?? null;

$fileBaseUrl = 'https://o2c.flowpetroleum.com.pk:9144/portalflowapis/uploads/';

if (!$work_order_id || !is_numeric($work_order_id) || !$current_user_id) {
    echo json_encode(["status" => false, "error" => "Invalid request"]);
    exit;
}

// 🔍 Get current user's role
$roleQuery = "SELECT privilege FROM users WHERE id = ?";
$roleStmt = $db->prepare($roleQuery);
$roleStmt->bind_param("i", $current_user_id);
$roleStmt->execute();
$roleResult = $roleStmt->get_result();

if (!$roleResult || $roleResult->num_rows === 0) {
    echo json_encode(["status" => false, "error" => "User not found"]);
    exit;
}

$current_user_role = $roleResult->fetch_assoc()['privilege'];




$query = "
   SELECT 
    woc.*, 
    CASE 
        WHEN woc.pivilege = 'user' THEN u.name
        WHEN woc.pivilege = 'dealer' THEN d.name
        ELSE 'Unknown'
    END AS user_name
FROM work_order_chat woc
LEFT JOIN users u ON woc.user_id = u.id AND woc.pivilege = 'user'
LEFT JOIN dealers d ON woc.user_id = d.id AND woc.pivilege = 'dealer'
WHERE woc.work_order_id = ?
ORDER BY woc.created_at ASC;

";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $work_order_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];

// 🔍 Helper: check if user is assigned to the dealer from dealers table
function isUserLinkedToDealer($db, $dealer_id, $user_id) {
    $query = "SELECT 1 FROM dealers WHERE id = ? AND (zm = ? OR tm = ? OR asm = ? OR created_by = ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiiii", $dealer_id, $user_id, $user_id, $user_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// 🔄 Loop through messages
while ($row = $result->fetch_assoc()) {
    $dealer_id = $row['dealer_id'];
    $isAllowed = false;

    // Admin (user_id = 1) or user linked to dealer
    if ($current_user_id == 1 || $current_user_role === 'admin') {
        $isAllowed = true;
    } elseif (isUserLinkedToDealer($db, $dealer_id, $current_user_id)) {
        $isAllowed = true;
    }

    if ($isAllowed) {
        $filePath = '';
        if ($row['type'] === 'file' && !empty($row['file_path'])) {
            $filePath = $fileBaseUrl . basename($row['file_path']);
        }

        $messages[] = [
            "id" => $row['id'],
            "user_id" => $row['user_id'],
            "user" => $row['user_name'],
            "message" => $row['message'],
            "type" => $row['type'],
            "file_path" => $filePath,
            "created_at" => $row['created_at']
        ];
    }
}

// ✅ Final JSON response
echo json_encode([
    "status" => true,
    "count" => count($messages),
    "messages" => $messages
]);

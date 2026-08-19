<?php
function insertLog(
    mysqli $conn,
    ?int $userId,
    ?string $user_name,
    string $action,
    ?string $description,
    string $status,
    string $ipAddress,
    ?string $userAgent
) {
    // Sanitize inputs
    $userId = $userId !== null ? (int)$userId : 0;
    $user_name = $user_name !== null ? htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') : 'unknown';
    $action = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');
    $description = $description !== null ? htmlspecialchars($description, ENT_QUOTES, 'UTF-8') : '';
    $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
    $ipAddress = filter_var($ipAddress, FILTER_VALIDATE_IP) ?: '0.0.0.0';
    $userAgent = $userAgent !== null ? htmlspecialchars($userAgent, ENT_QUOTES, 'UTF-8') : 'unknown';

    // Prepared statement
    $sql = "INSERT INTO log_activity 
        (user_id, username, action, description, status, ip_address, user_agent, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "issssss",
            $userId,
            $user_name,
            $action,
            $description,
            $status,
            $ipAddress,
            $userAgent
        );

        if (!$stmt->execute()) {
            error_log("Log insertion failed: " . $stmt->error);
        }

        $stmt->close();
    } else {
        error_log("Prepare failed for insertLog: " . $conn->error);
    }
}
?>

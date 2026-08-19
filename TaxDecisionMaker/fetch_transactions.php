
<?php include('setting/header.php'); ?>
<?php include('../db/connection.php'); ?>
<?php
// fetch_transactions.php
header('Content-Type: application/json');

if (!isset($_GET['filenumber']) || empty($_GET['filenumber'])) {
    echo json_encode([]);
    exit;
}

$filenumber = $_GET['filenumber'];


// Fetch transactions for this land (only approved or pending ones)
$sql = "SELECT id, transaction_type, receiver_fullname, transaction_date 
        FROM clientslandtransactions 
        WHERE filenumber = ? AND status IN ('pending','approved')
        ORDER BY transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $filenumber);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($transactions);

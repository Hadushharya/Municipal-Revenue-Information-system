<?php
session_start();
//include('setting/header.php'); 
 //include('../db/connection.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filenumber = $_POST['filenumber'];
    $transaction_type = $_POST['transaction_type'];
    $seller_fullname = $_POST['seller_fullname'];
    $receiver_fullname = $_POST['receiver_fullname'];
    $receiver_id_number = $_POST['receiver_id_number'];
    $transferred_area = floatval($_POST['transferred_area']);
    $estimated_value = floatval($_POST['estimated_value']);
    $transaction_date = $_POST['transaction_date'];
    $tax_change_year = intval($_POST['tax_change_year']);
    $registered_by = $_POST['registered_by'];
    $registered_time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO clientslandtransactions 
        (filenumber, transaction_type, seller_fullname, receiver_fullname, receiver_id_number, transferred_area, estimated_value, transaction_date, tax_change_year, status, registered_by, registered_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)");

    $stmt->bind_param("sssssdssiss", 
        $filenumber, $transaction_type, $seller_fullname, $receiver_fullname, $receiver_id_number,
        $transferred_area, $estimated_value, $transaction_date, $tax_change_year, $registered_by, $registered_time
    );

    if ($stmt->execute()) {
        echo "<script>alert('ግብዓት ብትክክል ተመዝጊቡ።'); window.location.href = 'transaction_form.php';</script>";
    } else {
        echo "ተሳኢ ምዝገባ: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

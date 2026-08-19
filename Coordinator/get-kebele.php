
<?php include('../db/connection.php');
if (isset($_POST['year'])) {
    $year = $conn->real_escape_string($_POST['year']);

    $query = "SELECT DISTINCT levelofplace FROM clientslanddatataxdecides WHERE year = '$year' ORDER BY levelofplace ASC";
    $result = $conn->query($query);

    echo '<option value="">-- Select Kebele --</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['levelofplace']) . '">' . htmlspecialchars($row['levelofplace']) . '</option>';
    }
}
?>

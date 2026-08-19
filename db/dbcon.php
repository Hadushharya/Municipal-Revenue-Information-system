<?php
ob_start();
// Attempt MySQL server connection with prepared statements (though not needed for this basic connection)
$conn = mysqli_connect("localhost", "root", "", "revenue");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Example of prepared statement for future queries
// Prepared statement example: Select query with dynamic parameters
/* 
$query = "SELECT * FROM your_table WHERE your_column = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $parameter);  // "s" for string parameter

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch and process results here if needed

    // Close the statement
    mysqli_stmt_close($stmt);
}
*/

// Close connection
mysqli_close($conn);

?>

<?php
function getEthiopianDate($gregDate = null) {
    // Ethiopian months
    $months = [
        "መስከረም", "ጥቅምቲ", "ሕዳር", "ታህሳስ", "ጥሪ", "ለካቲት",
        "መጋቢት", "ሚያዝያ", "ግንቦት", "ሰነ", "ሐምለ", "ነሐሰ", "ጳጉሜን"
    ];

    // Use current date if not provided
    if ($gregDate === null) {
        $gregDate = new DateTime();
    } else {
        $gregDate = new DateTime($gregDate);
    }

    // Constants
    $OFFSET = 79372; // From JavaScript
    $DAY = 60 * 60 * 24; // seconds in a day

    // Get Julian Day Number
    $utcTimestamp = $gregDate->getTimestamp(); // seconds since epoch
    $daysSinceEpoch = $utcTimestamp / $DAY;

    // Total Ethiopian days since offset
    $ecDays = $OFFSET + $daysSinceEpoch;

    // Calculate Ethiopian date
    $year = 1745 + floor($ecDays / 365.25);
    $daysRemaining = $ecDays - floor(($year - 1745) * 365.25);

    // Adjust for leap year approximation error
    if ($year % 4 === 0) {
        $daysRemaining--;
    }

    if ((int)$daysRemaining === 0) {
        $year--;
        $month = 13;
        $day = ($year % 4 === 3) ? 6 : 5;
    } else {
        $month = ceil($daysRemaining / 30);
        $day = ($daysRemaining % 30 === 0) ? 30 : $daysRemaining % 30;
    }

    // Return full formatted Ethiopian date
    $monthName = $months[$month - 1];
    $formatted = sprintf(' %s %d, %d E.C',$monthName, $day, $year);

    return [
        'day' => (int)$day,
        'month' => (int)$month,
        'year' => (int)$year,
        'text' => $formatted
    ];
}

// Example use:
$ethDate = getEthiopianDate();
echo "-- Backup generated on Ethiopian Calendar: {$ethDate['text']}\n\n";
?>

                     

<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'revenuedatabase'; // <-- Change this to your DB name

// Define the 10 tables to back up
$tables = [
    'admin', 'clientslanddata', 'clientslanddatataxdecides', 'defaultpassword', 'kiraytariff',
    'log_activity', 'month', 'rule', 'users', 'visionmission','sellingtransferrules','clientslandtransactionpayments','clientslandtransactions'
];

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start building the backup SQL
$backupSql = "-- Backup generated on " . date('Ymd_His'). "\n\n";

foreach ($tables as $table) {
    // Drop table if exists
    $backupSql .= "DROP TABLE IF EXISTS `$table`;\n";

    // Get table structure
    $result = $conn->query("SHOW CREATE TABLE `$table`");
    if ($row = $result->fetch_row()) {
        $backupSql .= $row[1] . ";\n\n";
    }

    // Get table data
    $result = $conn->query("SELECT * FROM `$table`");
    while ($row = $result->fetch_assoc()) {
        $values = array_map(function ($value) use ($conn) {
            return isset($value) ? "'" . $conn->real_escape_string($value) . "'" : "NULL";
        }, array_values($row));
        $backupSql .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
    }
    $backupSql .= "\n\n";
}

// Auto-create 'backups' folder if not exists
$backupDir ='D:/SMRDB/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true); // Creates recursively
}

// Save backup file
$filename = $backupDir . '/backup_' . "{$ethDate['text']}" . '.sql';
file_put_contents($filename, $backupSql);

echo "<br>✅ Backup completed successfully.<br>📁 File saved as: $filename";
?>

<?php 
@ob_start();
include('db/connection.php');   
include('head_index.php'); 
session_start();
session_destroy();
include('sidebar.php'); 

// Fetch distinct years from database for the dropdown
$yearQuery = "SELECT DISTINCT YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) AS year_only FROM clientslanddatataxdecides ORDER BY year_only DESC";
$yearResult = $conn->query($yearQuery);

// Set default year to the current year or the selected year from the form
$selectedYear = isset($_POST['year']) ? $_POST['year'] : date("Y");

// SQL query to get monthly revenue data for the selected year
$sql = "SELECT MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y')) AS month1, SUM(total) AS total_sum 
        FROM clientslanddatataxdecides 
        WHERE YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) = $selectedYear
        GROUP BY month1
        ORDER BY month1 ASC";
$result = $conn->query($sql);

// Prepare data arrays
$months = [];
$revenues = [];

// Mapping Gregorian months to Ethiopian months
$monthConversion = [
    '5' => 'ጥሪ', '6' => 'ለካቲት', '7' => 'መጋቢት', '8' => 'ማያዝያ',
    '9' => 'ግንቦት', '10' => 'ሰነ', '11' => 'ሐምለ', '12' => 'ንሃሰ',
    '1' => 'መስከረም', '2' => 'ጥቅምቲ', '3' => 'ሕዳር', '4' => 'ታህሳስ'
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gregorianMonth = $row['month1'];
        $ethiopianMonth = $monthConversion[$gregorianMonth] ?? $gregorianMonth;
        $months[] = $ethiopianMonth;
        $revenues[] = $row['total_sum'];
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Dashboard</title>
    <script src="chart1.js"></script> <!-- Use the downloaded Chart.js file -->
</head>
<body>

<!-- Form for Year Selection -->
<div style="text-align: center;">
    <form method="POST" action="">
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <?php
            // Populate dropdown with years from the database
            if ($yearResult->num_rows > 0) {
                while ($yearRow = $yearResult->fetch_assoc()) {
                    $year = $yearRow['year_only'];
                    $selected = $year == $selectedYear ? "selected" : "";
                    echo "<option value='$year' $selected>$year</option>";
                }
            } else {
                echo "<option value=''>No data available</option>";
            }
            ?>
        </select>
		
        <button type="submit">Filter</button>
    </form>
</div>

<div>
    <h2 style="text-align: center;">ሓበሬታ ወርሓዊ እቶት</h2>
    <div style="width: 60%; margin: auto;">
        <canvas id="revenueChart1"></canvas>
    </div>

    <script>
        // Embed PHP data into JavaScript variables
        const labels = <?php echo json_encode($months); ?>;
        const data = <?php echo json_encode($revenues); ?>;

        // Create a bar chart using Chart.js
        const ctx = document.getElementById('revenueChart1').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'ጠቅላላ እቶት(ብር)',
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ወርሕታት'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'ጠቅላላ እቶት(ብር)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'ወርሓዊ እቶት'
                    }
                }
            }
        });
    </script>
</div>
</body>
</html>

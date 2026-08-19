<?php
@ob_start();
include('db/connection.php');   
include('head_index.php'); 
session_start();
session_destroy();
// include('time_now.php');
include('sidebar.php');

// SQL query to get monthly revenue data using prepared statement
$sql = "SELECT year, SUM(total) AS total_sum FROM clientslanddatataxdecides GROUP BY year ORDER BY year ASC";

// Initialize arrays
$years = [];      // For storing years
$revenues = [];   // For storing revenue values

if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];        // Assuming 'year' is a column in the table
            $revenues[] = $row['total_sum'];
        }
    }

    $stmt->close();
} else {
    echo "SQL Error: " . $conn->error;
}

ob_end_flush();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Dashboard</title>
    <script src="chart.js"></script> <!-- Load Chart.js -->
</head>
<body>


<div>
    <h2 style="text-align: center;">ሓበሬታ ዓመታዊ እቶት </h2>
    <div style="width: 60%; margin: auto;">
        <canvas id="revenueChart"></canvas> <!-- Canvas element where the chart will be rendered -->
    </div>

   <script>
    // Embed PHP data directly into JavaScript variables
    const labels = <?php echo json_encode($years); ?>; // Years array from PHP
    const data = <?php echo json_encode($revenues); ?>; // Revenues array from PHP

    // Create a bar chart using Chart.js
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar', // Type of chart
        data: {
            labels: labels, // X-axis labels (years)
            datasets: [{
                label: 'ጠቅላላ እቶት(ብር)', // Label for the dataset
                data: data, // Revenue values
                backgroundColor: 'rgba(0, 123, 255, 0.7)', // Bars' background color
                borderColor: 'rgba(0, 123, 255, 1)', // Bars' border color
                borderWidth: 1 // Bars' border width
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'ዓ/ም' // Label for the x-axis
                    }
                },
                y: {
                    beginAtZero: true, // Start y-axis at 0
                    title: {
                        display: true,
                        text: 'ጠቅላላ እቶት(ብር)' // Label for the y-axis
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: '' // Chart title
                }
            }
        }
    });
</script>
</div>

</body>
</html>

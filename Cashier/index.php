<?php 
include('setting/header.php'); 
include('../db/connection.php');

if(!isset($_SESSION['SESS_ID'])) {
    die("Session error: User not logged in");
}
$user_id = $_SESSION['SESS_ID'];

// Include Ethiopian date converter
require_once('ethiopian_date_converter.php');
$today_ethiopian_string = gregorian_to_ethiopian_string(date('Y'), date('m'), date('d'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Dashboard</title>
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- ACE styles -->
    <link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style">
    <link rel="stylesheet" href="../assets/css/ace-skins.min.css">
    <link rel="stylesheet" href="../assets/css/ace-rtl.min.css">
    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/ace-elements.min.js"></script>
    <script src="../assets/js/ace.min.js"></script>
<style>
    .dashboard-table {
        border-collapse: collapse;
        width: 100%;
        text-align: center;
        font-family: Arial, sans-serif;
        font-size: 15px;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .dashboard-table thead {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
        font-size: 16px;
    }
    .dashboard-table th, .dashboard-table td {
        padding: 12px;
        border: 1px solid #ddd;
    }
    .dashboard-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    /* Category name column */
    .category-cell {
        font-weight: bold;
        text-align: left;
    }
    /* Color styles matching original cards */
    .daily-income {
        background-color: #32CD32;
        color: white;
        font-weight: bold;
    }
    .daily-remaining {
        background-color: #FFA500;
        color: white;
        font-weight: bold;
    }
    .total-waiting {
        background-color: #1E90FF;
        color: white;
        font-weight: bold;
    }
    /* Hover effect */
    .dashboard-table tbody tr:hover {
        background-color: #f1f1f1;
        transition: background 0.3s ease;
    }
</style>
</head>

<body class="no-skin">
<?php include('setting/headernav1.php'); ?>

<div class="main-container ace-save-state" id="main-container">
    <div id="sidebar" class="sidebar responsive ace-save-state">
        <?php include('setting/menu.php'); ?>
        <script type="text/javascript">
            try { ace.settings.loadState('sidebar') } catch (e) {}
        </script>
    </div>

    <div class="main-content">
        <div class="page-content">

<?php
$today = mysqli_real_escape_string($conn, $today_ethiopian_string);

/* ================== Land Tax ================== */
$lt_income_query = "
    SELECT SUM(total) AS daily_income, COUNT(*) AS daily_transactions
    FROM clientslanddatataxdecides
    WHERE LEFT(paidtime, 10) = '$today'
      AND TRIM(LOWER(forpayment_status)) = 'approved'
      AND TRIM(LOWER(forcashierstatus)) = 'approved'
";
$lt_income_data = mysqli_fetch_assoc(mysqli_query($conn, $lt_income_query));

$lt_wait_query = "
    SELECT SUM(total) AS waiting_amount, COUNT(*) AS waiting_count
    FROM clientslanddatataxdecides
    WHERE (TRIM(LOWER(forpayment_status)) IN ('approved', 'notapproved'))
      AND TRIM(LOWER(forcashierstatus)) = 'notapproved'
";
$lt_wait_data = mysqli_fetch_assoc(mysqli_query($conn, $lt_wait_query));

$lt_remain_query = "
    SELECT SUM(total) AS remaining_amount, COUNT(*) AS remaining_count
    FROM clientslanddatataxdecides
    WHERE LEFT(paidtime, 10) = '$today'
      AND (TRIM(LOWER(forpayment_status)) IN ('approved', 'notapproved'))
      AND TRIM(LOWER(forcashierstatus)) = 'notapproved'
";
$lt_remain_data = mysqli_fetch_assoc(mysqli_query($conn, $lt_remain_query));

/* ================== Home Rent ================== */
$hr_income_query = "
    SELECT SUM(total) AS daily_income, COUNT(*) AS daily_transactions
    FROM home_rent_payments
    WHERE LEFT(paiddate, 10) = '$today'
      AND status = 'approved'
";
$hr_income_data = mysqli_fetch_assoc(mysqli_query($conn, $hr_income_query));

$hr_wait_query = "
    SELECT SUM(total) AS waiting_amount, COUNT(*) AS waiting_count
    FROM home_rent_payments
    WHERE status = 'notapproved'
";
$hr_wait_data = mysqli_fetch_assoc(mysqli_query($conn, $hr_wait_query));

$hr_remain_query = "
    SELECT SUM(total) AS waiting_amount, COUNT(*) AS waiting_count
    FROM home_rent_payments
    WHERE LEFT(paiddate, 10) = '$today'
      AND status = 'notapproved'
";
$hr_remain_data = mysqli_fetch_assoc(mysqli_query($conn, $hr_remain_query));

/* ================== Land Transfer Payments ================== */
$ltp_income_query = "
    SELECT SUM(amount) AS daily_income, COUNT(*) AS daily_transactions
    FROM clientslandtransactionpayments
    WHERE LEFT(paidtime, 10) = '$today'
      AND forcashierstatus = 'approved'
";
$ltp_income_data = mysqli_fetch_assoc(mysqli_query($conn, $ltp_income_query));

$ltp_wait_query = "
    SELECT SUM(amount) AS waiting_amount, COUNT(*) AS waiting_count
    FROM clientslandtransactionpayments
    WHERE forcashierstatus = 'notapproved'
";
$ltp_wait_data = mysqli_fetch_assoc(mysqli_query($conn, $ltp_wait_query));

$ltp_remain_query = "
    SELECT SUM(amount) AS waiting_amount, COUNT(*) AS waiting_count
    FROM clientslandtransactionpayments
    WHERE LEFT(paidtime, 10) = '$today'
      AND forcashierstatus = 'notapproved'
";
$ltp_remain_data = mysqli_fetch_assoc(mysqli_query($conn, $ltp_remain_query));

/* ================== Other Payments ================== */
// Replace with actual queries if available
$other_income = 0;
$other_tx = 0;
$other_wait = 0;
$other_remain = 0;
?>

<h3 class="header smaller lighter blue text-center mb-4">
    <i class="ace-icon fa fa-dashboard"></i> ናይ ትሓዚ/ት ገንዝብ ዋና ገፅ - ማዓልታዊ ሓፈሻዊ ፀብፃብ
    <small class="text-muted">(<?php echo $today_ethiopian_string; ?>)</small>
</h3>

<table class="dashboard-table">
    <thead>
        <tr>
            <th>መደብ</th>
            <th>ማዓልታዊ ኣታዊ</th>
            <th>ማዓልታዊ ቀሪ</th>
            <th>ጠቅላላ ቀሪ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="category-cell"><i class="fa fa-money" ></i><font class='blue'> ግብሪ መሬት</font></td>
            <td class="daily-income"><?php echo number_format($lt_income_data['daily_income'] ?? 0, 2); ?> ብር<br><small><?php echo $lt_income_data['daily_transactions'] ?? 0; ?> TX</small></td>
            <td class="daily-remaining"><?php echo number_format($lt_remain_data['remaining_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $lt_remain_data['remaining_count'] ?? 0; ?> TX</small></td>
            <td class="total-waiting"><?php echo number_format($lt_wait_data['waiting_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $lt_wait_data['waiting_count'] ?? 0; ?> TX</small></td>
        </tr>
        <tr>
            <td class="category-cell"><i class="fa fa-home"></i><font class='blue'> ክራይን ኣካራይን</font></td>
            <td class="daily-income"><?php echo number_format($hr_income_data['daily_income'] ?? 0, 2); ?> ብር<br><small><?php echo $hr_income_data['daily_transactions'] ?? 0; ?> TX</small></td>
            <td class="daily-remaining"><?php echo number_format($hr_remain_data['waiting_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $hr_remain_data['waiting_count'] ?? 0; ?> TX</small></td>
            <td class="total-waiting"><?php echo number_format($hr_wait_data['waiting_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $hr_wait_data['waiting_count'] ?? 0; ?> TX</small></td>
        </tr>
        <tr>
            <td class="category-cell"><i class="fa fa-exchange"></i><font class='blue'> ዝዉዉር መሬት</font></td>
            <td class="daily-income"><?php echo number_format($ltp_income_data['daily_income'] ?? 0, 2); ?> ብር<br><small><?php echo $ltp_income_data['daily_transactions'] ?? 0; ?> TX</small></td>
            <td class="daily-remaining"><?php echo number_format($ltp_remain_data['waiting_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $ltp_remain_data['waiting_count'] ?? 0; ?> TX</small></td>
            <td class="total-waiting"><?php echo number_format($ltp_wait_data['waiting_amount'] ?? 0, 2); ?> ብር<br><small><?php echo $ltp_wait_data['waiting_count'] ?? 0; ?> TX</small></td>
        </tr>
        <tr>
            <td class="category-cell"><i class="fa fa-credit-card"></i> <font class='blue'>ካልኦት ክፍሊታት</font></td>
            <td class="daily-income"><?php echo number_format($other_income, 2); ?> ብር<br><small><?php echo $other_tx; ?> TX</small></td>
            <td class="daily-remaining"><?php echo number_format($other_remain, 2); ?> ብር<br><small>0 TX</small></td>
            <td class="total-waiting"><?php echo number_format($other_wait, 2); ?> ብር<br><small>0 TX</small></td>
        </tr>
    </tbody>
</table>



        </div> <!-- page-content -->
    </div> <!-- main-content -->
</div> <!-- main-container -->

<?php include('../footerboot.php'); ?>

<script type="text/javascript">
    // Safe window load for multiple scripts
    window.addEventListener('load', function(){
        // Load ACE main container state
        try { ace.settings.loadState('main-container'); } catch(e){}

        // You can call your Ethiopian date or show2() functions here if needed
        // displayEthiopianDate();
        // show2();
    });
</script>
</body>
</html>

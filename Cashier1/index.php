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
        .circle-card {
            width: 120px; height: 120px; border-radius: 50%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            color: white; font-weight: bold; margin: 10px auto;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2); transition: transform 0.2s; font-size: 0.85rem;
        }
        .circle-card:hover { transform: scale(1.05); }
        .circle-icon { font-size: 24px; margin-bottom: 5px; opacity: 0.8; }
        .circle-title { font-size: 12px; }
        .circle-value { font-size: 16px; }
        .circle-row { display: flex; justify-content: space-around; flex-wrap: wrap; margin-bottom: 30px; }
        .circle-category { text-align: center; margin-bottom: 15px; }
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

            <h3 class="header smaller lighter blue text-center mb-4">
                <i class="ace-icon fa fa-dashboard"></i> Cashier Dashboard - Daily Overview
                <small class="text-muted">(<?php echo $today_ethiopian_string; ?>)</small>
            </h3>

            <!-- ================== Land Tax Column ================== -->
            <div class="circle-category">
                <h4 class="text-primary"><i class="fa fa-money"></i> Land Tax</h4>
                <div class="circle-row">
                    <?php
                    $lt_income_query = "
                        SELECT SUM(total) AS daily_income, COUNT(*) AS daily_transactions
                        FROM clientslanddatataxdecides
                        WHERE LEFT(paidtime, 10) = '$today_ethiopian_string'
                          AND TRIM(LOWER(forpayment_status)) = 'approved'
                          AND TRIM(LOWER(forcashierstatus)) = 'approved'";
                    $lt_income_result = mysqli_query($conn, $lt_income_query);
                    $lt_income_data = mysqli_fetch_assoc($lt_income_result);

                    $lt_wait_query = "
                        SELECT SUM(total) AS waiting_amount, COUNT(*) AS waiting_count
                        FROM clientslanddatataxdecides
                        WHERE forpayment_status='approved' AND forcashierstatus='notapproved'";
                    $lt_wait_result = mysqli_query($conn, $lt_wait_query);
                    $lt_wait_data = mysqli_fetch_assoc($lt_wait_result);
                    ?>
                    <div class="circle-card" style="background: #1E90FF;">
                        <div class="circle-icon"><i class="fa fa-money"></i></div>
                        <div class="circle-title">Daily Income</div>
                        <div class="circle-value"><?php echo number_format($lt_income_data['daily_income'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $lt_income_data['daily_transactions'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #FFA500;">
                        <div class="circle-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="circle-title">Waiting</div>
                        <div class="circle-value"><?php echo number_format($lt_wait_data['waiting_amount'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $lt_wait_data['waiting_count'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #32CD32;">
                        <div class="circle-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="circle-title">Approved</div>
                        <div class="circle-value"><?php echo number_format($lt_income_data['daily_income'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $lt_income_data['daily_transactions'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #B2FF59;">
                        <div class="circle-icon"><i class="fa fa-hourglass-half"></i></div>
                        <div class="circle-title">Remaining</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                </div>
            </div>

            <!-- ================== Home Rent Column ================== -->
            <div class="circle-category">
                <h4 class="text-success"><i class="fa fa-home"></i> Home Rent</h4>
                <div class="circle-row">
                    <?php
                    $hr_income_query = "SELECT SUM(amount_paid) AS daily_income, COUNT(*) AS daily_transactions
                                        FROM home_rent_payments
                                        WHERE payment_date='$today_ethiopian_string' AND status='approved'";
                    $hr_income_result = mysqli_query($conn, $hr_income_query);
                    $hr_income_data = mysqli_fetch_assoc($hr_income_result);

                    $hr_wait_query = "SELECT SUM(amount_paid) AS waiting_amount, COUNT(*) AS waiting_count
                                      FROM home_rent_payments WHERE status='notapproved'";
                    $hr_wait_result = mysqli_query($conn, $hr_wait_query);
                    $hr_wait_data = mysqli_fetch_assoc($hr_wait_result);
                    ?>
                    <div class="circle-card" style="background: #00C853;">
                        <div class="circle-icon"><i class="fa fa-home"></i></div>
                        <div class="circle-title">Daily Income</div>
                        <div class="circle-value"><?php echo number_format($hr_income_data['daily_income'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $hr_income_data['daily_transactions'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #FFC107;">
                        <div class="circle-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="circle-title">Waiting</div>
                        <div class="circle-value"><?php echo number_format($hr_wait_data['waiting_amount'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $hr_wait_data['waiting_count'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #2979FF;">
                        <div class="circle-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="circle-title">Approved</div>
                        <div class="circle-value"><?php echo number_format($hr_income_data['daily_income'] ?? 0,2); ?> ብር</div>
                        <small><?php echo $hr_income_data['daily_transactions'] ?? 0; ?> TX</small>
                    </div>

                    <div class="circle-card" style="background: #B2FF59;">
                        <div class="circle-icon"><i class="fa fa-hourglass-half"></i></div>
                        <div class="circle-title">Remaining</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                </div>
            </div>

            <!-- ================== Other Payments Column ================== -->
            <div class="circle-category">
                <h4 class="text-secondary"><i class="fa fa-credit-card"></i> Other Payments</h4>
                <div class="circle-row">
                    <?php
                    // Placeholder values for other payments
                    $other_income = 0; $other_wait = 0; $other_tx = 0;
                    ?>
                    <div class="circle-card" style="background: #9C27B0;">
                        <div class="circle-icon"><i class="fa fa-credit-card"></i></div>
                        <div class="circle-title">Daily Income</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                    <div class="circle-card" style="background: #FF5722;">
                        <div class="circle-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="circle-title">Waiting</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                    <div class="circle-card" style="background: #00BCD4;">
                        <div class="circle-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="circle-title">Approved</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                    <div class="circle-card" style="background: #7B1FA2;">
                        <div class="circle-icon"><i class="fa fa-hourglass-half"></i></div>
                        <div class="circle-title">Remaining</div>
                        <div class="circle-value">0.00 ብር</div>
                        <small>0 TX</small>
                    </div>
                </div>
            </div>

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

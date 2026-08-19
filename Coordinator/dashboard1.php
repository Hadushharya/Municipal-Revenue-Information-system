<?php
// coordinator_dashboard.php
include('setting/header.php');
include('../db/connection.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['SESS_ID'], $_SESSION['SESS_USER_NAME'])) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['SESS_USER_NAME'];
$useridc  = $_SESSION['SESS_ID'];
include("../logactivity.php");

$monthNames = [
  1=>"መስከረም",2=>"ጥቅምቲ",3=>"ሕዳር",4=>"ታህሳስ",
  5=>"ጥሪ",6=>"ለካቲት",7=>"መጋቢት",8=>"ማያዝያ",
  9=>"ግንቦት",10=>"ሰነ",11=>"ሓምለ",12=>"ነሃሰ"
];

$selectedYear  = $_GET['year'] ?? '';
$selectedMonth = $_GET['month'] ?? '';
$selectedDay   = $_GET['day'] ?? '';

// ------------------ Build years ------------------
$years=[]; 
$sqlYears = "
SELECT DISTINCT YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS year_val FROM clientslanddatataxdecides WHERE paidtime != ''
UNION SELECT DISTINCT YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS year_val FROM clientslandtransactionpayments WHERE paidtime != ''
UNION SELECT DISTINCT YEAR(STR_TO_DATE(paiddate,'%d/%m/%Y')) AS year_val FROM home_rent_payments WHERE paiddate != ''
UNION SELECT DISTINCT YEAR(STR_TO_DATE(payment_date,'%d/%m/%Y')) AS year_val FROM other_payments WHERE payment_date != ''
ORDER BY year_val DESC";
$res = $conn->query($sqlYears);
while($row = $res->fetch_assoc()) $years[] = $row['year_val']; 
$res->free();

// ------------------ Build months ------------------
$months=[];
if($selectedYear){
    $sqlMonths = "
    SELECT DISTINCT MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS month_val FROM clientslanddatataxdecides WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y')) = $selectedYear
    UNION SELECT DISTINCT MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS month_val FROM clientslandtransactionpayments WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y')) = $selectedYear
    UNION SELECT DISTINCT MONTH(STR_TO_DATE(paiddate,'%d/%m/%Y')) AS month_val FROM home_rent_payments WHERE paiddate != '' AND YEAR(STR_TO_DATE(paiddate,'%d/%m/%Y')) = $selectedYear
    UNION SELECT DISTINCT MONTH(STR_TO_DATE(payment_date,'%d/%m/%Y')) AS month_val FROM other_payments WHERE payment_date != '' AND YEAR(STR_TO_DATE(payment_date,'%d/%m/%Y')) = $selectedYear
    ORDER BY month_val ASC";
    $res=$conn->query($sqlMonths); while($r=$res->fetch_assoc()) $months[]=$r['month_val']; $res->free();
}

// ------------------ Build days ------------------
$days=[];
if($selectedYear && $selectedMonth){
    $sqlDays = "
    SELECT DISTINCT DAY(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS day_val FROM clientslanddatataxdecides WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedYear AND MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedMonth
    UNION SELECT DISTINCT DAY(STR_TO_DATE(paidtime,'%d/%m/%Y')) AS day_val FROM clientslandtransactionpayments WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedYear AND MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedMonth
    UNION SELECT DISTINCT DAY(STR_TO_DATE(paiddate,'%d/%m/%Y')) AS day_val FROM home_rent_payments WHERE paiddate != '' AND YEAR(STR_TO_DATE(paiddate,'%d/%m/%Y'))=$selectedYear AND MONTH(STR_TO_DATE(paiddate,'%d/%m/%Y'))=$selectedMonth
    UNION SELECT DISTINCT DAY(STR_TO_DATE(payment_date,'%d/%m/%Y')) AS day_val FROM other_payments WHERE payment_date != '' AND YEAR(STR_TO_DATE(payment_date,'%d/%m/%Y'))=$selectedYear AND MONTH(STR_TO_DATE(payment_date,'%d/%m/%Y'))=$selectedMonth
    ORDER BY day_val ASC";
    $res=$conn->query($sqlDays); while($r=$res->fetch_assoc()) $days[]=$r['day_val']; $res->free();
}

// ------------------ Helper function for chart ------------------
function fetchChartData($conn,$table,$dateField,$amountFields,$where='',$groupBy=''){
    if(!$groupBy) return [[],[]]; // Prevent empty GROUP BY
    
    $amountSum = implode(",", array_map(fn($f) => "SUM($f) AS $f", $amountFields));
    $query = "SELECT $groupBy AS label, $amountSum FROM $table";
    if($where) $query.=" WHERE $where";
    $query.=" GROUP BY $groupBy ORDER BY $groupBy ASC";
    
    $res = $conn->query($query);
    $labels=[]; $dataFields=[];
    foreach($amountFields as $f) $dataFields[$f]=[];
    $dataFields['total'] = [];
    if($res){ 
        while($r=$res->fetch_assoc()){
            $labels[]=$r['label'];
            foreach($amountFields as $f) $dataFields[$f][]=(float)$r[$f];
            $dataFields['total'][] = array_sum(array_map(fn($f) => (float)$r[$f], $amountFields));
        } 
        $res->free();
    }
    return [$labels,$dataFields];
}

// ------------------ Prepare Charts ------------------

// Chart 1: clientslanddatataxdecides
$where1="paidtime != ''";
if($selectedYear) $where1.=" AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedYear";
if($selectedMonth) $where1.=" AND MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedMonth";
if($selectedDay) $where1.=" AND DAY(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedDay";
$groupBy1 = !$selectedYear ? "YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))" : (!$selectedMonth ? "MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))" : "DAY(STR_TO_DATE(paidtime,'%d/%m/%Y'))");
list($barLabels1,$data1)=fetchChartData($conn,'clientslanddatataxdecides','paidtime',['amountofpay','punishment','bankinterest','clean'],$where1,$groupBy1);

// Chart 2: clientslandtransactionpayments
$where2="paidtime != '' AND forcashierstatus='approved'";
if($selectedYear) $where2.=" AND YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedYear";
if($selectedMonth) $where2.=" AND MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedMonth";
if($selectedDay) $where2.=" AND DAY(STR_TO_DATE(paidtime,'%d/%m/%Y'))=$selectedDay";
$groupBy2 = !$selectedYear ? "YEAR(STR_TO_DATE(paidtime,'%d/%m/%Y'))" : (!$selectedMonth ? "MONTH(STR_TO_DATE(paidtime,'%d/%m/%Y'))" : "DAY(STR_TO_DATE(paidtime,'%d/%m/%Y'))");
list($barLabels2,$data2)=fetchChartData($conn,'clientslandtransactionpayments','paidtime',['amount'],$where2,$groupBy2);

// Chart 3: home_rent_payments
$where3="paiddate != '' AND status='approved'";
if($selectedYear) $where3.=" AND YEAR(STR_TO_DATE(paiddate,'%d/%m/%Y'))=$selectedYear";
if($selectedMonth) $where3.=" AND MONTH(STR_TO_DATE(paiddate,'%d/%m/%Y'))=$selectedMonth";
if($selectedDay) $where3.=" AND DAY(STR_TO_DATE(paiddate,'%d/%m/%Y'))=$selectedDay";
$groupBy3 = !$selectedYear ? "YEAR(STR_TO_DATE(paiddate,'%d/%m/%Y'))" : (!$selectedMonth ? "MONTH(STR_TO_DATE(paiddate,'%d/%m/%Y'))" : "DAY(STR_TO_DATE(paiddate,'%d/%m/%Y'))");
list($barLabels3,$data3)=fetchChartData($conn,'home_rent_payments','paiddate',['amount_paid','bankinterest','punishment'],$where3,$groupBy3);

// Chart 4: other_payments
$where4="payment_date != '' AND status='approved'";
if($selectedYear) $where4.=" AND YEAR(STR_TO_DATE(payment_date,'%d/%m/%Y'))=$selectedYear";
if($selectedMonth) $where4.=" AND MONTH(STR_TO_DATE(payment_date,'%d/%m/%Y'))=$selectedMonth";
if($selectedDay) $where4.=" AND DAY(STR_TO_DATE(payment_date,'%d/%m/%Y'))=$selectedDay";
$groupBy4 = !$selectedYear ? "YEAR(STR_TO_DATE(payment_date,'%d/%m/%Y'))" : (!$selectedMonth ? "MONTH(STR_TO_DATE(payment_date,'%d/%m/%Y'))" : "DAY(STR_TO_DATE(payment_date,'%d/%m/%Y'))");
list($barLabels4,$data4)=fetchChartData($conn,'other_payments','payment_date',['amount'],$where4,$groupBy4);

// Log activity
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
insertLog($conn, $useridc, $username, "Report Generation", "Report Generated dynamically.", "success", $ip, $agent);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Coordinator Dashboard - Offline</title>
<script src="assets/js/chart.min.js"></script>
<style>
.container { max-width:1200px; margin:20px auto; padding:10px; }
.row { display:flex; flex-wrap:wrap; gap:16px; }
.col-md-6 { flex:0 0 49%; }
.form-select, .btn { width:100%; padding:8px; margin-bottom:10px; }
canvas { background:#fff; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.08); }
</style>
</head>
<body class="container">
<h3>Coordinator Dashboard (Offline)</h3>

<form method="GET" class="row" style="margin-bottom:18px;">
<div class="col-md-3"><label>ዓ/ም</label>
<select name="year" class="form-select" onchange="this.form.submit()">
<option value="">All</option>
<?php foreach($years as $y){ $sel=($selectedYear==$y)?'selected':''; ?><option value="<?= $y ?>" <?= $sel ?>><?= $y ?></option><?php } ?>
</select></div>

<div class="col-md-3"><label>ወርሒ</label>
<select name="month" class="form-select" onchange="this.form.submit()">
<option value="">All</option>
<?php foreach($months as $m){ $lbl=$monthNames[(int)$m]??$m; $sel=($selectedMonth==$m)?'selected':''; ?><option value="<?= $m ?>" <?= $sel ?>><?= $lbl ?></option><?php } ?>
</select></div>

<div class="col-md-3"><label>ማዓልቲ</label>
<select name="day" class="form-select" onchange="this.form.submit()">
<option value="">All</option>
<?php foreach($days as $d){ $sel=($selectedDay==$d)?'selected':''; ?><option value="<?= $d ?>" <?= $sel ?>><?= $d ?></option><?php } ?>
</select></div>

<div class="col-md-3" style="align-self:end;"><button type="submit" class="btn">ፍለ/Filter</button></div>
</form>

<div class="row" style="gap:20px;">
  <div class="col-md-5">
    <h5>ካብ ግብሪ መሬት</h5>
    <canvas id="barChart1"></canvas>
  </div>
  <div class="col-md-5">
    <h5>ካብ መሬት ዝውውር</h5>
    <canvas id="barChart2"></canvas>
  </div>
  <div class="col-md-5">
    <h5>ካብ ገዛ ክራይ </h5>
    <canvas id="barChart3"></canvas>
  </div>
  <div class="col-md-5">
    <h5>ካብ ካልኦት ክፍሊታት</h5>
    <canvas id="barChart4"></canvas>
  </div>
</div>


<script>
new Chart(document.getElementById('barChart1'),{
    type:'bar',
    data:{labels:<?=json_encode($barLabels1)?>,datasets:[
        {label:'amountofpay',data:<?=json_encode($data1['amountofpay'])?>,backgroundColor:'#007bff'},
        {label:'punishment',data:<?=json_encode($data1['punishment'])?>,backgroundColor:'#dc3545'},
        {label:'bankinterest',data:<?=json_encode($data1['bankinterest'])?>,backgroundColor:'#ffc107'},
        {label:'clean',data:<?=json_encode($data1['clean'])?>,backgroundColor:'#28a745'},
        {label:'total',data:<?=json_encode($data1['total'])?>,backgroundColor:'#6c757d'}
    ]},
    options:{responsive:true, plugins:{tooltip:{mode:'index', intersect:false}}}
});

new Chart(document.getElementById('barChart2'),{
    type:'bar',
    data:{labels:<?=json_encode($barLabels2)?>,datasets:[
        {label:'amount',data:<?=json_encode($data2['amount'])?>,backgroundColor:'#17a2b8'},
        {label:'total',data:<?=json_encode($data2['total'])?>,backgroundColor:'#6c757d'}
    ]},
    options:{responsive:true, plugins:{tooltip:{mode:'index', intersect:false}}}
});

new Chart(document.getElementById('barChart3'),{
    type:'bar',
    data:{labels:<?=json_encode($barLabels3)?>,datasets:[
        {label:'amount_paid',data:<?=json_encode($data3['amount_paid'])?>,backgroundColor:'#007bff'},
        {label:'bankinterest',data:<?=json_encode($data3['bankinterest'])?>,backgroundColor:'#ffc107'},
        {label:'punishment',data:<?=json_encode($data3['punishment'])?>,backgroundColor:'#dc3545'},
        {label:'total',data:<?=json_encode($data3['total'])?>,backgroundColor:'#28a745'}
    ]},
    options:{responsive:true, plugins:{tooltip:{mode:'index', intersect:false}}}
});

new Chart(document.getElementById('barChart4'),{
    type:'bar',
    data:{labels:<?=json_encode($barLabels4)?>,datasets:[
        {label:'amount',data:<?=json_encode($data4['amount'])?>,backgroundColor:'#fd7e14'},
        {label:'total',data:<?=json_encode($data4['total'])?>,backgroundColor:'#6c757d'}
    ]},
    options:{responsive:true, plugins:{tooltip:{mode:'index', intersect:false}}}
});
</script>
</body>
</html>

<?php include('../db/connection.php');
            $username=$_SESSION['SESS_USER_NAME'];
            $useridc=$_SESSION['SESS_ID'];	
            include("../logactivity.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Coordinator Dashboard</title>
 <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
  
</head>
<body class="container py-2">
<h3>Dynamic Dashboard for Coordinator</h3>

<form method="GET" class="row g-3 mb-4">
  <div class="col-md-3">
    <label>ዓ/ም</label>
    <select name="year" class="form-select" onchange="this.form.submit()">
      <option value="">All</option>
      <?php 
        $query = "SELECT DISTINCT YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) as year FROM clientslanddatataxdecides WHERE paidtime != '' ORDER BY year DESC";
        $result = mysqli_query($conn, $query);
        $selectedYear = $_GET['year'] ?? '';
        while ($row = mysqli_fetch_array($result)) {
          $year = $row['year'];
          $selected = ($selectedYear == $year) ? 'selected' : '';
          echo "<option value='$year' $selected>$year</option>";
        }
      ?>
    </select>
  </div>
  <div class="col-md-3">
    <label>ወርሒ</label>
    <select name="month" class="form-select" onchange="this.form.submit()">
      <option value="">All</option>
      <?php
      $selectedMonth = $_GET['month'] ?? '';
      if ($selectedYear) {
        $stmt = $conn->prepare("SELECT DISTINCT MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y')) as month FROM clientslanddatataxdecides WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) = ? ORDER BY month ASC");
        $stmt->bind_param("i", $selectedYear);
        $stmt->execute();
        $result = $stmt->get_result();
      } else {
        $result = $conn->query("SELECT DISTINCT MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y')) as month FROM clientslanddatataxdecides WHERE paidtime != '' ORDER BY month ASC");
      }
      $monthNames = [1=>"መስከረም", 2=>"ጥቅምቲ", 3=>"ሕዳር", 4=>"ታህሳስ", 5=>"ጥሪ", 6=>"ለካቲት", 7=>"መጋቢት", 8=>"ማያዝያ", 9=>"ግንቦት", 10=>"ሰነ", 11=>"ሓምለ", 12=>"ንሃሰ", 13=>"ዻጉሜን"];
      while ($row = $result->fetch_assoc()) {
        $m = (int)$row['month'];
        $monthName = $monthNames[$m] ?? $m;
        $selected = ($selectedMonth == $m) ? 'selected' : '';
        echo "<option value='$m' $selected>$monthName</option>";
      }
      ?>
    </select>
  </div>
  <div class="col-md-3">
    <label>ማዓልቲ</label>
    <select name="day" class="form-select" onchange="this.form.submit()">
      <option value="">All</option>
      <?php
      $selectedDay = $_GET['day'] ?? '';
      if ($selectedYear && $selectedMonth) {
        $stmt = $conn->prepare("SELECT DISTINCT DAY(STR_TO_DATE(paidtime, '%d/%m/%Y')) as day FROM clientslanddatataxdecides WHERE paidtime != '' AND YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) = ? AND MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y')) = ? ORDER BY day ASC");
        $stmt->bind_param("ii", $selectedYear, $selectedMonth);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
          $d = (int)$row['day'];
          $selected = ($selectedDay == $d) ? 'selected' : '';
          echo "<option value='$d' $selected>$d</option>";
        }
      }
      ?>
    </select>
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">ፍለ/Filter</button>
  </div>
</form>

<?php
$year = $_GET['year'] ?? '';
$month = $_GET['month'] ?? '';
$day = $_GET['day'] ?? '';
$where = "paidtime != ''";
if ($year && is_numeric($year)) $where .= " AND YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y')) = $year";
if ($month && is_numeric($month)) $where .= " AND MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y')) = $month";
if ($day && is_numeric($day)) $where .= " AND DAY(STR_TO_DATE(paidtime, '%d/%m/%Y')) = $day";
if (!$year) {
    $groupBy = "YEAR(STR_TO_DATE(paidtime, '%d/%m/%Y'))";
    $labelField = "$groupBy AS label";
    $labelText = "ዓመት";
} elseif ($year && !$month) {
    $groupBy = "MONTH(STR_TO_DATE(paidtime, '%d/%m/%Y'))";
    $labelField = "$groupBy AS label";
    $labelText = "ወርሒ";
} else {
    $groupBy = "DAY(STR_TO_DATE(paidtime, '%d/%m/%Y'))";
    $labelField = "$groupBy AS label";
    $labelText = "ማዓልቲ";
}
$query = "SELECT $labelField, SUM(amountofpay) AS total_amount, SUM(punishment) AS total_punishment, SUM(bankinterest) AS total_interest, SUM(clean) AS total_clean, SUM(amountofpay + punishment + bankinterest + clean) AS total_sum FROM clientslanddatataxdecides WHERE $where GROUP BY $groupBy ORDER BY $groupBy ASC";
$result = $conn->query($query);
$barLabels = $sumPayments = $sumPunishments = $sumInterest = $sumClean = $sumTotal = [];
while ($row = $result->fetch_assoc()) {
  $label = $row['label'];
  if ($labelText === "ወርሒ") {
    $label = $monthNames[(int)$label] ?? $label;
  }
  $barLabels[] = $label;
  $sumPayments[] = (float)$row['total_amount'];
  $sumPunishments[] = (float)$row['total_punishment'];
  $sumInterest[] = (float)$row['total_interest'];
  $sumClean[] = (float)$row['total_clean'];
  $sumTotal[] = (float)$row['total_sum'];
}
//  Record log activity
$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
insertLog($conn, $useridc, $username, "Report Generation", "Report Generated dynamically.", "success", $ip, $agent);  
//$payDecided = (int)$conn->query("SELECT COUNT(*) AS c FROM clientslanddatataxdecides WHERE $where AND forpayment_status='approved' AND forcashierstatus='notapproved'")->fetch_assoc()['c'];
$NotDecided = (int)$conn->query("SELECT COUNT(*) AS c FROM clientslanddatataxdecides WHERE $where AND forpayment_status!='approved' AND forcashierstatus='notapproved'")->fetch_assoc()['c'];
$cashApproved = (int)$conn->query("SELECT COUNT(*) AS c FROM clientslanddatataxdecides WHERE $where AND forpayment_status='approved' AND forcashierstatus='approved'")->fetch_assoc()['c'];
$cashNot = (int)$conn->query("SELECT COUNT(*) AS c FROM clientslanddatataxdecides WHERE $where AND forpayment_status='approved' AND forcashierstatus!='approved'")->fetch_assoc()['c'];
?>
<div class="row">
  <div class="col-md-8">
    <canvas id="barChart" height="150"></canvas>
  </div>
  <div class="col-md-4">
    <canvas id="pieChart" height="150"></canvas>
  </div>
</div>
<script>
const barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($barLabels) ?>,
    datasets: [
      { label: 'ዝኣተወ መጠን ገንዘብ (ብር)', data: <?= json_encode($sumPayments) ?>, backgroundColor: '#007bff' },
      { label: 'ቅፅዓት (ብር)', data: <?= json_encode($sumPunishments) ?>, backgroundColor: '#dc3545' },
      { label: 'ባንኪ ወለድ (ብር)', data: <?= json_encode($sumInterest) ?>, backgroundColor: '#ffc107' },
      { label: 'ናይ ፅሬት (ብር)', data: <?= json_encode($sumClean) ?>, backgroundColor: '#20c997' },
      { label: 'ጠቕላላ መጠን ገንዘብ(ብር)', data: <?= json_encode($sumTotal) ?>, backgroundColor: '#6f42c1' }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top',
            labels: { font: { size: 9 } }	
			},
	  
      tooltip: { mode: 'index', intersect: false }
    },
    scales: {
      x: { title: { display: true, text: "<?= $labelText ?>" } },
      y: { beginAtZero: true, title: { display: true, text: 'መጠን (ብር)' } }
    }
  }
});
const pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
  type: 'pie',
  data: {
    labels: ['ግብሪ ዘይተወሰነሎምን ናብ ትሓዚ ገንዘብ ዘይተልኣኩን', 'ዝከፈሉ', 'ዘይከፈሉ(ግብሪ ዝተወሰነሎም ናብ ትሓዚ ገንዘብ ዝተልኣኩ)'],
    datasets: [{
      data: [ <?= $NotDecided ?>, <?= $cashApproved ?>, <?= $cashNot ?>],
      backgroundColor: [ '#ffc107', '#007bff', '#ff6384']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      tooltip: {
        callbacks: {
          label: function(context) {
            let label = context.label || '';
            let value = context.parsed || 0;
            let sum = context.chart._metasets[context.datasetIndex].total;
            let percentage = sum ? (value / sum * 100).toFixed(2) : 0;
            return label + ': ' + value + ' (' + percentage + '%)';
          }
        }
      }
    }
  }
});
</script>
</body>
</html>
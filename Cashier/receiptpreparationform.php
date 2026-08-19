<?php
// ==== Bootstrapping & Security ====
if (session_status() === PHP_SESSION_NONE) session_start();

include('setting/header.php');
include('../db/connection.php');

if (!isset($_SESSION['SESS_ID']) || !isset($_SESSION['SESS_USER_NAME'])) {
    header("Location: ../login.php"); exit;
}

$username = $_SESSION['SESS_USER_NAME'];
$useridc  = (int) $_SESSION['SESS_ID'];

include('../logactivity.php');

// ==== Helper: flash messages ====
function set_flash($msg, $type='success'){
    $_SESSION['flash'] = ['msg'=>$msg,'type'=>$type];
}
function take_flash(){
    if(!empty($_SESSION['flash'])){ $f=$_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// ==== Handle Add/Update Code (acc_cod, income_type) ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_name']) && $_POST['form_name']==='codes_form') {
    $acc_cod     = isset($_POST['acc_cod']) ? trim($_POST['acc_cod']) : '';
    $income_type = isset($_POST['income_type']) ? trim($_POST['income_type']) : '';

    if ($acc_cod === '' || !ctype_digit($acc_cod)) {
        set_flash("Acc Code is required and must be numeric.", 'danger');
    } elseif ($income_type === '') {
        set_flash("Income Type is required.", 'danger');
    } else {
        // Upsert: if acc_cod exists -> update, else insert
        $stmt = $conn->prepare("SELECT 1 FROM codes WHERE acc_cod=?");
        $stmt->bind_param("s", $acc_cod);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $stmt = $conn->prepare("UPDATE codes SET income_type=? WHERE acc_cod=?");
            $stmt->bind_param("ss", $income_type, $acc_cod);
            $stmt->execute();
            $stmt->close();
            set_flash("Code {$acc_cod} updated.");
            insertLog($conn, $useridc, $username, "Codes Update", "Updated acc_cod {$acc_cod}", "success", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? null);
        } else {
            $stmt = $conn->prepare("INSERT INTO codes (acc_cod, income_type) VALUES (?, ?)");
            $stmt->bind_param("ss", $acc_cod, $income_type);
            $stmt->execute();
            $stmt->close();
            set_flash("Code {$acc_cod} added.");
            insertLog($conn, $useridc, $username, "Codes Add", "Added acc_cod {$acc_cod}", "success", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? null);
        }
    }
    // PRG: avoid resubmission; preserve query string for receipt IDs
    header("Location: " . strtok($_SERVER['REQUEST_URI'],'?') . (empty($_SERVER['QUERY_STRING'])?'':'?'.$_SERVER['QUERY_STRING']));
    exit;
}

// ==== Fetch current user full name ====
$stmtU = $conn->prepare("SELECT fname,mname,lname FROM users WHERE id=?");
$stmtU->bind_param("i", $useridc);
$stmtU->execute();
$resU = $stmtU->get_result();
$userfullname = '';
if ($u = $resU->fetch_assoc()) $userfullname = $u['fname'].' '.$u['mname'].' '.$u['lname'];
$stmtU->close();

// ==== Get receipt filters (userid + ids) ====
$client_userid = isset($_GET['userid']) ? (int)$_GET['userid'] : 0;
$idList = isset($_GET['id']) ? array_map('intval', explode(',', $_GET['id'])) : [];
$receiptReady = ($client_userid && !empty($idList));

// ==== Fetch Codes for table & mapping ====
$codes = [];
$rCodes = $conn->query("SELECT acc_cod, income_type FROM codes ORDER BY CAST(acc_cod AS UNSIGNED) ASC");
while ($r = $rCodes->fetch_assoc()) $codes[] = $r;

// ==== Number to Tigrinya ====
function numberToTigrinya($number) {
    $tigrinyaNumbers = [
        0 => "ባዶ",
        1 => "ሓደ",
        2 => "ክልተ",
        3 => "ሰለስተ",
        4 => "ኣርባዕተ",
        5 => "ሓሙሽተ",
        6 => "ሽዱሽተ",
        7 => "ሸውዓተ",
        8 => "ሸሞንተ",
        9 => "ትሽዓተ",
        10 => "ዓሰርተ",
        20 => "ዒስራ",
        30 => "ሰላሳ",
        40 => "አርብዓ",
        50 => "ሓምሳ",
        60 => "ስሳ",
        70 => "ሰብዓ",
        80 => "ሰማንያ",
        90 => "ቴስዓ",
        100 => "ሚኢቲ",
        1000 => "ሽሕ"
    ];

    // Round to 4 decimal places for letters
    $number = round($number, 2);

    // Split integer and decimal parts
    $parts = explode('.', number_format($number, 2, '.', ''));
    $integerPart = intval($parts[0]);
    $decimalPart = isset($parts[1]) ? rtrim($parts[1], '0') : null;

    $text = "";

 // Integer part
if ($integerPart == 0) {
    $text = $tigrinyaNumbers[0];
} elseif ($integerPart < 10) {
    $text = $tigrinyaNumbers[$integerPart];
} elseif ($integerPart < 20) {
    $text = $tigrinyaNumbers[10] . " " . $tigrinyaNumbers[$integerPart - 10];
} elseif ($integerPart < 100) {
    $tens = intval($integerPart / 10) * 10;
    $units = $integerPart % 10;

    // Append 'ን' to both tens and units
    $text = $tigrinyaNumbers[$tens] . "ን" . ($units ? " " . $tigrinyaNumbers[$units] . "ን" : "");

} elseif ($integerPart < 1000) {
    $hundreds = intval($integerPart / 100);
    $remainder = $integerPart % 100;
    $hundredText = ($hundreds == 1) ? "ሓደ ሚኢቲ" : $tigrinyaNumbers[$hundreds] . " ሚኢቲ";
    if ($remainder > 0) $hundredText .= "ን"; // Add 'ን' when there is remainder
    $text = $hundredText;
    if ($remainder > 0) $text .= " " . numberToTigrinya($remainder);
} elseif ($integerPart < 1000000) {
    $thousands = intval($integerPart / 1000);
    $remainder = $integerPart % 1000;
    $thousandText = ($thousands == 1) ? "ሓደ ሽሕ" : numberToTigrinya($thousands) . " ሽሕ";
    if ($remainder > 0) $thousandText .= "ን"; // Add 'ን' when remainder exists
    $text = $thousandText;
    if ($remainder > 0) $text .= " " . numberToTigrinya($remainder);
} else {
    $text = $integerPart;
}

    // Decimal part
    if ($decimalPart) {
        $text .= " ነጥቢ";
        foreach (str_split($decimalPart) as $digit) {
            $text .= " " . $tigrinyaNumbers[intval($digit)];
        }
    }

    return $text;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Codes & Receipt</title>
    <link rel="stylesheet" href="print.css">
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.bootstrap.min.css">
    <style>
        .container-pad { padding: 15px; }
        .card { background:#fff;border:1px solid #ddd;border-radius:10px;padding:16px;margin-bottom:18px; }
        .card h3 { margin-top:0; }
        #printableDiv table { width:100%; border-collapse: collapse; }
        #printableDiv th, #printableDiv td { border:2px solid #000; padding:6px; }
        @media print {
            body * { visibility: hidden; }
            #printableDiv, #printableDiv * { visibility: visible; }
            #printableDiv { position:absolute; left:0; top:0; width:100%; }
        }
        /* optional: emphasize debit row (4101) */
        .debit-row { font-weight: 600; }
    </style>
</head>
<body class="no-skin">
<?php include('setting/headernav1.php'); ?>
<div class="main-container ace-save-state" id="main-container">
    <script>try{ace.settings.loadState('main-container')}catch(e){}</script>

    <div id="sidebar" class="sidebar responsive ace-save-state">
        <script>try{ace.settings.loadState('sidebar')}catch(e){}</script>
        <?php include('setting/menu.php'); ?>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
               data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <?php include('setting/settingpage.php'); ?>
            <div class="container-pad">

                <?php if ($f = take_flash()) { ?>
                    <div class="alert alert-<?php echo ($f['type']==='danger') ? 'danger' : 'success'; ?>">
                        <?php echo htmlspecialchars($f['msg']); ?>
                    </div>
                <?php } ?>

                <div class="card">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                       
                        <button class="btn btn-success" onclick="window.print();">Print Receipt</button>
                    </div>

                    <div id="printableDiv">
                        <?php if (!$receiptReady) { ?>
                            <p><em>Provide <code>?userid=...&id=1,2,3</code> in the URL to render a receipt.</em></p>
                        <?php } else {
                            // Prepare placeholders for IN (...)
                            $placeholders = implode(',', array_fill(0, count($idList), '?'));
                            $sql = "
                                SELECT 
                                    c.clientid, c.userid, c.fullname, c.filenumber, c.paidtime, c.paidby, c.year, c.kindofvolumeofland,
                                    GROUP_CONCAT(DISTINCT c.receiptnumber ORDER BY c.receiptnumber ASC) AS land_receipt,
                                    GROUP_CONCAT(DISTINCT c.year ORDER BY c.year ASC) AS land_years,
                                    SUM(c.amountofpay) AS sumAmount,
                                    SUM(c.bankinterest) AS sumBankInterest,
                                    SUM(c.punishment) AS sumPunishment,
                                    SUM(c.clean) AS sumClean,
                                    SUM(c.total) AS sumTotal,
                                    GROUP_CONCAT(DISTINCT h.payment_month ORDER BY h.payment_month ASC) AS rent_years,
                                    SUM(h.amount_paid) AS sumRent,
                                    GROUP_CONCAT(DISTINCT h.receipt_no ORDER BY h.receipt_no ASC) AS rent_receipt
                                FROM clientslanddatataxdecides c
                                LEFT JOIN home_rent_payments h
                                    ON c.clientid=h.homeowner_id
                                    AND c.year=h.payment_month
                                    AND h.status='approved'
                                WHERE c.forpayment_status='approved'
                                  AND c.forcashierstatus='approved'
                                  AND c.userid=?
                                  AND c.id IN ($placeholders)
                                GROUP BY c.clientid, DATE(c.registeredtime)
                                ORDER BY c.registeredtime ASC, c.clientid ASC
                            ";
                            $stmt = $conn->prepare($sql);
                            // build params: first userid then ids
                            $params = array_merge([$client_userid], $idList);
                            $types  = 'i' . str_repeat('i', count($idList)); // all ints
                            // prepare references for bind_param
                            $bind_names = [];
                            $bind_names[] = $types;
                            foreach ($params as $idx => $p) {
                                // bind_param requires references
                                $bind_names[] = &$params[$idx];
                            }
                            call_user_func_array([$stmt, 'bind_param'], $bind_names);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            // Mapping with side info (ensure side is defined)
                            $codeToColumn = [
                                '4101' => ['col'=>'sumTotal',      'side'=>'debit'],
                                '1773' => ['col'=>'sumBankInterest','side'=>'credit'],
                                '1746' => ['col'=>'sumPunishment', 'side'=>'credit'],
                                '1751' => ['col'=>'sumClean',      'side'=>'credit'],
                                '1722' => ['col'=>'sumAmount',     'side'=>'credit'],
                                '1800' => ['col'=>'sumRent',       'side'=>'credit']
                            ];

                            while ($row = $res->fetch_assoc()) {
                                // Build map from codes table (so the descriptions come from DB)
                                $accMap = [];
                                foreach ($codes as $c) {
                                    $code = (string)$c['acc_cod'];
                                    $desc = $c['income_type'];
                                    $value = 0.0;
                                    $side = 'credit'; // default side
                                    if (isset($codeToColumn[$code])) {
                                        $col  = $codeToColumn[$code]['col'];
                                        $side = $codeToColumn[$code]['side'] ?? 'credit';
                                        $value = isset($row[$col]) ? (float)$row[$col] : 0.0;
                                    }
                                    // ALWAYS set 'side' so template won't warn
                                    $accMap[$code] = ['desc' => $desc, 'value' => $value, 'side' => $side];
                                }

                                // Totals per side
                                $totalDebit = 0.0;
                                $totalCredit = 0.0;
                                foreach ($accMap as $cVal) {
                                    if (!empty($cVal['value'])) {
                                        if (($cVal['side'] ?? 'credit') === 'debit') $totalDebit += $cVal['value'];
                                        else $totalCredit += $cVal['value'];
                                    }
                                }

                                // Paid amount shown in words: sum of both sides
                                $paidAmount = $totalDebit + $totalCredit;

                                // Payment reason text
                                $reason = (($row['sumTotal'] ?? 0) != 0 && ($row['sumRent'] ?? 0) != 0) ? "ናይ ግብሪ መሬትን ክራይን መካረይን"
                                         : ((($row['sumTotal'] ?? 0) != 0) ? "ናይ ግብሪ መሬት"
                                         : ((( $row['sumRent'] ?? 0) != 0) ? "ክራይን መካረይን" : "—"));
                                ?>
                                <div style="margin-bottom:22px;">
                                    <h4 style="text-align:center;"><u><b>ናይ ግብሪ ከፍላይ ቅብሊት ቅጥዒ</b></u></h4>
                                    <p style="text-align:right;">ደረሰይ ቁፅሪ: <b><?= htmlspecialchars($row['land_receipt']) ?></b></p>
                                    <p style="text-align:right;">ዕለት: <b><?= htmlspecialchars($row['paidtime']) ?></b></p>
                                    <p>ሙሉእ ሽም ከፋሊ ግብሪ: <u><b><?= htmlspecialchars($row['fullname']) ?></b></u></p>
                                    <p>ከፋሊ ግብሪ ፋ/ቁፅሪ: <b><?= htmlspecialchars($row['filenumber']) ?></b></p>
                                    <p>ናይቲ መሬት ዓይነት ትሕዝቶ: <b><?= htmlspecialchars($row['kindofvolumeofland']) ?></b></p>
                                    <p>ምክንያት ክፍሊት: <b><?= $reason ?></b></p>
                                    <p>ግብሪ ዘመን: <b><?= htmlspecialchars($row['year']) ?></b></p>
                                    
                                  <style>
/* Stronger table borders */
.table-bordered th, 
.table-bordered td {
    border: 2px solid #000 !important; /* darker, thicker lines */
    padding: 6px;
    text-align: center;
    vertical-align: middle;
}

.table-bordered {
    border: 2px solid #000 !important;
    border-collapse: collapse !important;
}

.table-bordered thead th {
    background: #f5f5f5; /* light gray header */
    font-weight: bold;
}
</style>

<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>ሒሳብ ኮድ<br>Acc Code</th>
            <th>ኣታዊ ዓይነት <br>Income Type</th>
            <th>ደቢት<br>Debit</th>
            <th>ክሬዲት<br>Credit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // --- Print 4101 first ---
        $debit4101 = 0.0;
        if (isset($accMap['4101'])) {
            $val = $accMap['4101'];
            $debit4101  = ($val['side'] === 'debit') ? $val['value'] : 0.0;
            $credit4101 = ($val['side'] === 'credit') ? $val['value'] : 0.0;
            ?>
            <tr class="debit-row">
                <td><?= htmlspecialchars('4101') ?></td>
                <td><?= htmlspecialchars($val['desc']) ?></td>
                <td><?= $debit4101 ? number_format($debit4101,2) : '0.00' ?></td>
                <td><?= $credit4101 ? number_format($credit4101,2) : '0.00' ?></td>
            </tr>
            <?php
            unset($accMap['4101']); // avoid duplicate
        }

        // --- Print remaining codes ---
        $totalDebit = $debit4101; // include 4101
        $totalCredit = 0.0;
        foreach ($accMap as $code => $val) {
            if (!empty($val['value'])) {
                $side   = $val['side'] ?? 'credit';
                $debit  = ($side === 'debit') ? $val['value'] : 0.0;
                $credit = ($side === 'credit') ? $val['value'] : 0.0;
                $rowClass = ($side === 'debit') ? 'debit-row' : '';
                ?>
                <tr class="<?= $rowClass ?>">
                    <td><?= htmlspecialchars($code) ?></td>
                    <td><?= htmlspecialchars($val['desc']) ?></td>
                    <td><?= $debit ? number_format($debit,2) : '0.00' ?></td>
                    <td><?= $credit ? number_format($credit,2) : '0.00' ?></td>
                </tr>
                <?php
                $totalDebit += $debit;
                $totalCredit += $credit;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">ድምር</th>
            <th><?= number_format($totalDebit,2) ?></th>
            <th><?= number_format($totalCredit,2) ?></th>
        </tr>
    </tfoot>
</table>



                                    <p><b>ብፊደል : <?= numberToTigrinya($totalDebit) ?></b></p>

<div style="display:inline-block; text-align:center;">
    <p>ስም ተቐባሊ ገንዘብ:
       <u><b><?= htmlspecialchars($row['paidby']) ?></b></u>
    </p>
    <p>ፌርማ: ____________</p>
</div>

                                </div>
                                <hr>
                                <?php
                            } // end while
                        } // end else ($receiptReady)
                        ?>
                    </div>
                </div>


                            <hr>
                            <?php
                                insertLog($conn, $useridc, $username, "Receipt Prepared", "Receipt Generated.", "success", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? null);
                            //endwhile;
                            $stmt->close();
                            ?>
                        <?php //endif; ?>
                    </div>
                </div>

            </div><!-- /.container-pad -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- jQuery first (Ace may already include it; fallback to CDN) -->
<script src="../assets/js/jquery-2.1.4.min.js"></script>

<!-- DataTables (use Ace bundle already in your page set) -->
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/jquery.dataTables.bootstrap.min.js"></script>

<script>
jQuery(function($){
    // Interactive codes table
    $('#codesTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10,25,50,-1],[10,25,50,'All']]
    });
});
</script>
</body>
</html>
                            
                                </h4>
                                        </div> 
<div>                                       
            </div>
                <!-- /.main-content -->
                 
        </div><!-- /.main-container -->
            <!-- /.main-content -->

        
        <?php include('../footerboot.php'); ?>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="../assets/js/jquery-2.1.4.min.js"></script>

        <!-- <![endif]-->

        <!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
        <script type="text/javascript">
            if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>
        <script src="../assets/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="assets/js/excanvas.min.js"></script>
        <![endif]-->
        <script src="../assets/js/jquery-ui.custom.min.js"></script>
        <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
        <script src="../assets/js/jquery.easypiechart.min.js"></script>
        <script src="../assets/js/jquery.sparkline.index.min.js"></script>
        <script src="../assets/js/jquery.flot.min.js"></script>
        <script src="../assets/js/jquery.flot.pie.min.js"></script>
        <script src="../assets/js/jquery.flot.resize.min.js"></script>

        <!-- ace scripts -->
        <script src="../assets/js/ace-elements.min.js"></script>
        <script src="../assets/js/ace.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->
        <script src="../assets/js/jquery.dataTables.min.js"></script>
        <script src="../assets/js/jquery.dataTables.bootstrap.min.js"></script>
        <script src="../assets/js/dataTables.buttons.min.js"></script>
        <script src="../assets/js/buttons.flash.min.js"></script>
        <script src="../assets/js/buttons.html5.min.js"></script>
        <script src="../assets/js/buttons.print.min.js"></script>
        <script src="../assets/js/buttons.colVis.min.js"></script>
        <script src="../assets/js/dataTables.select.min.js"></script>

        <!-- ace scripts -->
        <script src="../assets/js/ace-elements.min.js"></script>
        <script src="../assets/js/ace.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<script src="lib/jquery.js" type="text/javascript"></script>-->
<script src="src/facebox.js" type="text/javascript"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<!--<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>-->
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<!--<script src="js/jquery.js"></script>-->
        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function($) {
                $('.easy-pie-chart.percentage').each(function(){
                    var $box = $(this).closest('.infobox');
                    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
                    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
                    var size = parseInt($(this).data('size')) || 50;
                    $(this).easyPieChart({
                        barColor: barColor,
                        trackColor: trackColor,
                        scaleColor: false,
                        lineCap: 'butt',
                        lineWidth: parseInt(size/10),
                        animate: ace.vars['old_ie'] ? false : 1000,
                        size: size
                    });
                })
            
                $('.sparkline').each(function(){
                    var $box = $(this).closest('.infobox');
                    var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
                    $(this).sparkline('html',
                                     {
                                        tagValuesAttribute:'data-values',
                                        type: 'bar',
                                        barColor: barColor ,
                                        chartRangeMin:$(this).data('min') || 0
                                     });
                });
            
            
              //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
              //but sometimes it brings up errors with normal resize event handlers
              $.resize.throttleWindow = false;
            
              var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
              var data = [
                { label: "<?php echo "$nrcact_id";?>",  data: <?php echo "$rcact_id";?>, color: "#68BC31"},
                { label: "<?php echo "$ntact_id";?>",  data: <?php echo "$tact_id";?>, color: "#2091CF"},
                { label: " <?php echo "$ncact_id";?>",  data: <?php echo "$cact_id";?>, color: "#AF4E96"}, 
              ]
              function drawPieChart(placeholder, data, position) {
                  $.plot(placeholder, data, {
                    series: {
                        pie: {
                            show: true,
                            tilt:0.8,
                            highlight: {
                                opacity: 0.25
                            },
                            stroke: {
                                color: '#fff',
                                width: 2
                            },
                            startAngle: 2
                        }
                    },
                    legend: {
                        show: true,
                        position: position || "ne", 
                        labelBoxBorderColor: null,
                        margin:[-30,15]
                    }
                    ,
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                 })
             }
             drawPieChart(placeholder, data);
            
             /**
             we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
             so that's not needed actually.
             */
             placeholder.data('chart', data);
             placeholder.data('draw', drawPieChart);
            
            
              //pie chart tooltip example
              var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
              var previousPoint = null;
            
              placeholder.on('plothover', function (event, pos, item) {
                if(item) {
                    if (previousPoint != item.seriesIndex) {
                        previousPoint = item.seriesIndex;
                        var tip = item.series['label'] + " : " + item.series['percent']+'%';
                        $tooltip.show().children(0).text(tip);
                    }
                    $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
                } else {
                    $tooltip.hide();
                    previousPoint = null;
                }
                
             });
            
                /////////////////////////////////////
                $(document).one('ajaxloadstart.page', function(e) {
                    $tooltip.remove();
                });
            
            
            
            
                var d1 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.5) {
                    d1.push([i, Math.sin(i)]);
                }
            
                var d2 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.5) {
                    d2.push([i, Math.cos(i)]);
                }
            
                var d3 = [];
                for (var i = 0; i < Math.PI * 2; i += 0.2) {
                    d3.push([i, Math.tan(i)]);
                }
                
            
                var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
                $.plot("#sales-charts", [
                    { label: "Domains", data: d1 },
                    { label: "Hosting", data: d2 },
                    { label: "Sers", data: d3 }
                ], {
                    hoverable: true,
                    shadowSize: 0,
                    series: {
                        lines: { show: true },
                        points: { show: true }
                    },
                    xaxis: {
                        tickLength: 0
                    },
                    yaxis: {
                        ticks: 10,
                        min: -2,
                        max: 2,
                        tickDecimals: 3
                    },
                    grid: {
                        backgroundColor: { colors: [ "#fff", "#fff" ] },
                        borderWidth: 1,
                        borderColor:'#555'
                    }
                });
            
            
                $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
                function tooltip_placement(context, source) {
                    var $source = $(source);
                    var $parent = $source.closest('.tab-content')
                    var off1 = $parent.offset();
                    var w1 = $parent.width();
            
                    var off2 = $source.offset();
                    //var w2 = $source.width();
            
                    if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                    return 'left';
                }
            
            
                $('.dialogs,.comments').ace_scroll({
                    size: 300
                });
                
                
                //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
                //so disable dragging when clicking on label
                var agent = navigator.userAgent.toLowerCase();
                if(ace.vars['touch'] && ace.vars['android']) {
                  $('#tasks').on('touchstart', function(e){
                    var li = $(e.target).closest('#tasks li');
                    if(li.length == 0)return;
                    var label = li.find('label.inline').get(0);
                    if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
                  });
                }
            
                $('#tasks').sortable({
                    opacity:0.8,
                    revert:true,
                    forceHelperSize:true,
                    placeholder: 'draggable-placeholder',
                    forcePlaceholderSize:true,
                    tolerance:'pointer',
                    stop: function( event, ui ) {
                        //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                        $(ui.item).css('z-index', 'auto');
                    }
                    }
                );
                $('#tasks').disableSelection();
                $('#tasks input:checkbox').removeAttr('checked').on('click', function(){
                    if(this.checked) $(this).closest('li').addClass('selected');
                    else $(this).closest('li').removeClass('selected');
                });
            
                //show the dropdowns on top or bottom depending on window height and menu position
                $('#task-tab .dropdown-hover').on('mouseenter', function(e) {
                    var offset = $(this).offset();
            
                    var $w = $(window)
                    if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
                        $(this).addClass('dropup');
                    else $(this).removeClass('dropup');
                });
            
            })
        </script>
                <script src="../assets/js/jquery-ui.custom.min.js"></script>
        <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
        <script src="../assets/js/chosen.jquery.min.js"></script>
        <script src="../assets/js/spinbox.min.js"></script>
        <script src="../assets/js/bootstrap-datepicker.min.js"></script>
        <script src="../assets/js/bootstrap-timepicker.min.js"></script>
        <script src="../assets/js/moment.min.js"></script>
        <script src="../assets/js/daterangepicker.min.js"></script>
        <script src="../assets/js/bootstrap-datetimepicker.min.js"></script>
        <script src="../assets/js/bootstrap-colorpicker.min.js"></script>
        <script src="../assets/js/jquery.knob.min.js"></script>
        <script src="../assets/js/autosize.min.js"></script>
        <script src="../assets/js/jquery.inputlimiter.min.js"></script>
        <script src="../assets/js/jquery.maskedinput.min.js"></script>
        <script src="../assets/js/bootstrap-tag.min.js"></script>

        <!-- ace scripts -->
        <script src="../assets/js/ace-elements.min.js"></script>
        <script src="../assets/js/ace.min.js"></script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function($) {
                $('#id-disable-check').on('click', function() {
                    var inp = $('#form-input-readonly').get(0);
                    if(inp.hasAttribute('disabled')) {
                        inp.setAttribute('readonly' , 'true');
                        inp.removeAttribute('disabled');
                        inp.value="This text field is readonly!";
                    }
                    else {
                        inp.setAttribute('disabled' , 'disabled');
                        inp.removeAttribute('readonly');
                        inp.value="This text field is disabled!";
                    }
                });
            
            
                if(!ace.vars['touch']) {
                    $('.chosen-select').chosen({allow_single_deselect:true}); 
                    //resize the chosen on window resize
            
                    $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                             var $this = $(this);
                             $this.next().css({'width': $this.parent().width()});
                        })
                    }).trigger('resize.chosen');
                    //resize chosen on sidebar collapse/expand
                    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                        if(event_name != 'sidebar_collapsed') return;
                        $('.chosen-select').each(function() {
                             var $this = $(this);
                             $this.next().css({'width': $this.parent().width()});
                        })
                    });
            
            
                    $('#chosen-multiple-style .btn').on('click', function(e){
                        var target = $(this).find('input[type=radio]');
                        var which = parseInt(target.val());
                        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                         else $('#form-field-select-4').removeClass('tag-input-style');
                    });
                }
            
            
                $('[data-rel=tooltip]').tooltip({container:'body'});
                $('[data-rel=popover]').popover({container:'body'});
            
                autosize($('textarea[class*=autosize]'));
                
                $('textarea.limited').inputlimiter({
                    remText: '%n character%s remaining...',
                    limitText: 'max allowed : %n.'
                });
            
                $.mask.definitions['~']='[+-]';
                $('.input-mask-date').mask('99/99/9999');
                $('.input-mask-phone').mask('(999) 999-9999');
                $('.input-mask-eyescript').mask('~9.99 ~9.99 999');
                $(".input-mask-product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
            
            
            
                $( "#input-size-slider" ).css('width','200px').slider({
                    value:1,
                    range: "min",
                    min: 1,
                    max: 8,
                    step: 1,
                    slide: function( event, ui ) {
                        var sizing = ['', 'input-sm', 'input-lg', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
                        var val = parseInt(ui.value);
                        $('#form-field-4').attr('class', sizing[val]).attr('placeholder', '.'+sizing[val]);
                    }
                });
            
                $( "#input-span-slider" ).slider({
                    value:1,
                    range: "min",
                    min: 1,
                    max: 12,
                    step: 1,
                    slide: function( event, ui ) {
                        var val = parseInt(ui.value);
                        $('#form-field-5').attr('class', 'col-xs-'+val).val('.col-xs-'+val);
                    }
                });
            
            
                
                //"jQuery UI Slider"
                //range slider tooltip example
                $( "#slider-range" ).css('height','200px').slider({
                    orientation: "vertical",
                    range: true,
                    min: 0,
                    max: 100,
                    values: [ 17, 67 ],
                    slide: function( event, ui ) {
                        var val = ui.values[$(ui.handle).index()-1] + "";
            
                        if( !ui.handle.firstChild ) {
                            $("<div class='tooltip right in' style='display:none;left:16px;top:-6px;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>")
                            .prependTo(ui.handle);
                        }
                        $(ui.handle.firstChild).show().children().eq(1).text(val);
                    }
                }).find('span.ui-slider-handle').on('blur', function(){
                    $(this.firstChild).hide();
                });
                
                
                $( "#slider-range-max" ).slider({
                    range: "max",
                    min: 1,
                    max: 10,
                    value: 2
                });
                
                $( "#slider-eq > span" ).css({width:'90%', 'float':'left', margin:'15px'}).each(function() {
                    // read initial values from markup and remove that
                    var value = parseInt( $( this ).text(), 10 );
                    $( this ).empty().slider({
                        value: value,
                        range: "min",
                        animate: true
                        
                    });
                });
                
                $("#slider-eq > span.ui-slider-purple").slider('disable');//disable third item
            
                
                $('#id-input-file-1 , #id-input-file-2').ace_file_input({
                    no_file:'No File ...',
                    btn_choose:'Choose',
                    btn_change:'Change',
                    droppable:false,
                    onchange:null,
                    thumbnail:false //| true | large
                    //whitelist:'gif|png|jpg|jpeg'
                    //blacklist:'exe|php'
                    //onchange:''
                    //
                });
                //pre-show a file name, for example a previously selected file
                //$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])
            
            
                $('#id-input-file-3').ace_file_input({
                    style: 'well',
                    btn_choose: 'Drop files here or click to choose',
                    btn_change: null,
                    no_icon: 'ace-icon fa fa-cloud-upload',
                    droppable: true,
                    thumbnail: 'small'//large | fit
                    //,icon_remove:null//set null, to hide remove/reset button
                    /**,before_change:function(files, dropped) {
                        //Check an example below
                        //or examples/file-upload.html
                        return true;
                    }*/
                    /**,before_remove : function() {
                        return true;
                    }*/
                    ,
                    preview_error : function(filename, error_code) {
                        //name of the file that failed
                        //error_code values
                        //1 = 'FILE_LOAD_FAILED',
                        //2 = 'IMAGE_LOAD_FAILED',
                        //3 = 'THUMBNAIL_FAILED'
                        //alert(error_code);
                    }
            
                }).on('change', function(){
                    //console.log($(this).data('ace_input_files'));
                    //console.log($(this).data('ace_input_method'));
                });
                
                
                //$('#id-input-file-3')
                //.ace_file_input('show_file_list', [
                    //{type: 'image', name: 'name of image', path: 'http://path/to/image/for/preview'},
                    //{type: 'file', name: 'hello.txt'}
                //]);
            
                
                
            
                //dynamically change allowed formats by changing allowExt && allowMime function
                $('#id-file-format').removeAttr('checked').on('change', function() {
                    var whitelist_ext, whitelist_mime;
                    var btn_choose
                    var no_icon
                    if(this.checked) {
                        btn_choose = "Drop images here or click to choose";
                        no_icon = "ace-icon fa fa-picture-o";
            
                        whitelist_ext = ["jpeg", "jpg", "png", "gif" , "bmp"];
                        whitelist_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];
                    }
                    else {
                        btn_choose = "Drop files here or click to choose";
                        no_icon = "ace-icon fa fa-cloud-upload";
                        
                        whitelist_ext = null;//all extensions are acceptable
                        whitelist_mime = null;//all mimes are acceptable
                    }
                    var file_input = $('#id-input-file-3');
                    file_input
                    .ace_file_input('update_settings',
                    {
                        'btn_choose': btn_choose,
                        'no_icon': no_icon,
                        'allowExt': whitelist_ext,
                        'allowMime': whitelist_mime
                    })
                    file_input.ace_file_input('reset_input');
                    
                    file_input
                    .off('file.error.ace')
                    .on('file.error.ace', function(e, info) {
                        //console.log(info.file_count);//number of selected files
                        //console.log(info.invalid_count);//number of invalid files
                        //console.log(info.error_list);//a list of errors in the following format
                        
                        //info.error_count['ext']
                        //info.error_count['mime']
                        //info.error_count['size']
                        
                        //info.error_list['ext']  = [list of file names with invalid extension]
                        //info.error_list['mime'] = [list of file names with invalid mimetype]
                        //info.error_list['size'] = [list of file names with invalid size]
                        
                        
                        /**
                        if( !info.dropped ) {
                            //perhapse reset file field if files have been selected, and there are invalid files among them
                            //when files are dropped, only valid files will be added to our file array
                            e.preventDefault();//it will rest input
                        }
                        */
                        
                        
                        //if files have been selected (not dropped), you can choose to reset input
                        //because browser keeps all selected files anyway and this cannot be changed
                        //we can only reset file field to become empty again
                        //on any case you still should check files with your server side script
                        //because any arbitrary file can be uploaded by user and it's not safe to rely on browser-side measures
                    });
                    
                    
                    /**
                    file_input
                    .off('file.preview.ace')
                    .on('file.preview.ace', function(e, info) {
                        console.log(info.file.width);
                        console.log(info.file.height);
                        e.preventDefault();//to prevent preview
                    });
                    */
                
                });
            
                $('#spinner1').ace_spinner({value:0,min:0,max:200,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
                .closest('.ace-spinner')
                .on('changed.fu.spinbox', function(){
                    //console.log($('#spinner1').val())
                }); 
                $('#spinner2').ace_spinner({value:0,min:0,max:10000,step:100, touch_spinner: true, icon_up:'ace-icon fa fa-caret-up bigger-110', icon_down:'ace-icon fa fa-caret-down bigger-110'});
                $('#spinner3').ace_spinner({value:0,min:-100,max:100,step:10, on_sides: true, icon_up:'ace-icon fa fa-plus bigger-110', icon_down:'ace-icon fa fa-minus bigger-110', btn_up_class:'btn-success' , btn_down_class:'btn-danger'});
                $('#spinner4').ace_spinner({value:0,min:-100,max:100,step:10, on_sides: true, icon_up:'ace-icon fa fa-plus', icon_down:'ace-icon fa fa-minus', btn_up_class:'btn-purple' , btn_down_class:'btn-purple'});
            
                //$('#spinner1').ace_spinner('disable').ace_spinner('value', 11);
                //or
                //$('#spinner1').closest('.ace-spinner').spinner('disable').spinner('enable').spinner('value', 11);//disable, enable or change value
                //$('#spinner1').closest('.ace-spinner').spinner('value', 0);//reset to 0
            
            
                //datepicker plugin
                //link
                $('.date-picker').datepicker({
                    autoclose: true,
                    todayHighlight: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
            
                //or change it into a date range picker
                $('.input-daterange').datepicker({autoclose:true});
            
            
                //to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
                $('input[name=date-range-picker]').daterangepicker({
                    'applyClass' : 'btn-sm btn-success',
                    'cancelClass' : 'btn-sm btn-default',
                    locale: {
                        applyLabel: 'Apply',
                        cancelLabel: 'Cancel',
                    }
                })
                .prev().on(ace.click_event, function(){
                    $(this).next().focus();
                });
            
            
                $('#timepicker1').timepicker({
                    minuteStep: 1,
                    showSeconds: true,
                    showMeridian: false,
                    disableFocus: true,
                    icons: {
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down'
                    }
                }).on('focus', function() {
                    $('#timepicker1').timepicker('showWidget');
                }).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
                
                
            
                
                if(!ace.vars['old_ie']) $('#date-timepicker1').datetimepicker({
                 //format: 'MM/DD/YYYY h:mm:ss A',//use this option to display seconds
                 icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-arrows ',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                 }
                }).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
                if(!ace.vars['old_ie']) $('#date-timepicker2').datetimepicker({
                 //format: 'MM/DD/YYYY h:mm:ss A',//use this option to display seconds
                 icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-arrows ',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                 }
                }).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
            
                $('#colorpicker1').colorpicker();
                //$('.colorpicker').last().css('z-index', 2000);//if colorpicker is inside a modal, its z-index should be higher than modal'safe
            
                $('#simple-colorpicker-1').ace_colorpicker();
                //$('#simple-colorpicker-1').ace_colorpicker('pick', 2);//select 2nd color
                //$('#simple-colorpicker-1').ace_colorpicker('pick', '#fbe983');//select #fbe983 color
                //var picker = $('#simple-colorpicker-1').data('ace_colorpicker')
                //picker.pick('red', true);//insert the color if it doesn't exist
            
            
                $(".knob").knob();
                
                
                var tag_input = $('#form-field-tags');
                try{
                    tag_input.tag(
                      {
                        placeholder:tag_input.attr('placeholder'),
                        //enable typeahead by specifying the source array
                        source: ace.vars['US_STATES'],//defined in ace.js >> ace.enable_search_ahead
                        /**
                        //or fetch data from database, fetch those that match "query"
                        source: function(query, process) {
                          $.ajax({url: 'remote_source.php?q='+encodeURIComponent(query)})
                          .done(function(result_items){
                            process(result_items);
                          });
                        }
                        */
                      }
                    )
            
                    //programmatically add/remove a tag
                    var $tag_obj = $('#form-field-tags').data('tag');
                    $tag_obj.add('Programmatically Added');
                    
                    var index = $tag_obj.inValues('some tag');
                    $tag_obj.remove(index);
                }
                catch(e) {
                    //display a textarea for old IE, because it doesn't support this plugin or another one I tried!
                    tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
                    //autosize($('#form-field-tags'));
                }
                
                
                /////////
                $('#modal-form input[type=file]').ace_file_input({
                    style:'well',
                    btn_choose:'Drop files here or click to choose',
                    btn_change:null,
                    no_icon:'ace-icon fa fa-cloud-upload',
                    droppable:true,
                    thumbnail:'large'
                })
                
                //chosen plugin inside a modal will have a zero width because the select element is originally hidden
                //and its width cannot be determined.
                //so we set the width after modal is show
                $('#modal-form').on('shown.bs.modal', function () {
                    if(!ace.vars['touch']) {
                        $(this).find('.chosen-container').each(function(){
                            $(this).find('a:first-child').css('width' , '210px');
                            $(this).find('.chosen-drop').css('width' , '210px');
                            $(this).find('.chosen-search input').css('width' , '200px');
                        });
                    }
                })
                /**
                //or you can activate the chosen plugin after modal is shown
                //this way select element becomes visible with dimensions and chosen works as expected
                $('#modal-form').on('shown', function () {
                    $(this).find('.modal-chosen').chosen();
                })
                */
            
                
                
                $(document).one('ajaxloadstart.page', function(e) {
                    autosize.destroy('textarea[class*=autosize]')
                    
                    $('.limiterBox,.autosizejs').remove();
                    $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
                });
            
            });
        </script>
                <script type="text/javascript">
            jQuery(function($) {
                //initiate dataTables plugin
                var myTable = 
                $('#dynamic-table')
                .wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                .DataTable( {
                    bAutoWidth: false,
                    "aoColumns": [
                      { "bSortable": false },
                       null, null, null,  null, null, null, null, null, null,  null, null, null,  null, null, null,  null, null, 
                      { "bSortable": false }
                    ],
                    "aaSorting": [],
                    
                    
                    //"bProcessing": true,
                    //"bServerSide": true,
                    //"sAjaxSource": "http://127.0.0.1/table.php"   ,
            
                    //,
                    //"sScrollY": "200px",
                    //"bPaginate": false,
            
                    //"sScrollX": "100%",
                    //"sScrollXInner": "120%",
                    //"bScrollCollapse": true,
                    //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                    //you may want to wrap the table inside a "div.dataTables_borderWrap" element
            
                    //"iDisplayLength": 50
            
            
                    select: {
                        style: 'multi'
                    }
                } );
            
                
                
                $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
                
                new $.fn.dataTable.Buttons( myTable, {
                    buttons: [
                      {
                        "extend": "colvis",
                        "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                        "className": "btn btn-white btn-primary btn-bold",
                        columns: ':not(:first):not(:last)'
                      },
                      {
                        "extend": "copy",
                        "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                        "className": "btn btn-white btn-primary btn-bold"
                      },
                      {
                        "extend": "csv",
                        "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                        "className": "btn btn-white btn-primary btn-bold"
                      },
                      {
                        "extend": "excel",
                        "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                        "className": "btn btn-white btn-primary btn-bold"
                      },
                      {
                        "extend": "pdf",
                        "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                        "className": "btn btn-white btn-primary btn-bold"
                      },
                      {
                        "extend": "print",
                        "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                        "className": "btn btn-white btn-primary btn-bold",
                        autoPrint: false,
                        message: 'This print was produced using the Print button for DataTables'
                      }       
                    ]
                } );
                myTable.buttons().container().appendTo( $('.tableTools-container') );
                
                //style the message box
                var defaultCopyAction = myTable.button(1).action();
                myTable.button(1).action(function (e, dt, button, config) {
                    defaultCopyAction(e, dt, button, config);
                    $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
                });
                
                
                var defaultColvisAction = myTable.button(0).action();
                myTable.button(0).action(function (e, dt, button, config) {
                    
                    defaultColvisAction(e, dt, button, config);
                    
                    
                    if($('.dt-button-collection > .dropdown-menu').length == 0) {
                        $('.dt-button-collection')
                        .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                        .find('a').attr('href', '#').wrap("<li />")
                    }
                    $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
                });
            
                ////
            
                setTimeout(function() {
                    $($('.tableTools-container')).find('a.dt-button').each(function() {
                        var div = $(this).find(' > div').first();
                        if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
                        else $(this).tooltip({container: 'body', title: $(this).text()});
                    });
                }, 500);
                
                
                
                
                
                myTable.on( 'select', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        $( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
                    }
                } );
                myTable.on( 'deselect', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        $( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
                    }
                } );
            
            
            
            
                /////////////////////////////////
                //table checkboxes
                $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
                
                //select/deselect all rows according to table header checkbox
                $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
                    var th_checked = this.checked;//checkbox inside "TH" table header
                    
                    $('#dynamic-table').find('tbody > tr').each(function(){
                        var row = this;
                        if(th_checked) myTable.row(row).select();
                        else  myTable.row(row).deselect();
                    });
                });
                
                //select/deselect a row when the checkbox is checked/unchecked
                $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
                    var row = $(this).closest('tr').get(0);
                    if(this.checked) myTable.row(row).deselect();
                    else myTable.row(row).select();
                });
            
            
            
                $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                });
                
                
                
                //And for the first simple table, which doesn't have TableTools or dataTables
                //select/deselect all rows according to table header checkbox
                var active_class = 'active';
                $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
                    var th_checked = this.checked;//checkbox inside "TH" table header
                    
                    $(this).closest('table').find('tbody > tr').each(function(){
                        var row = this;
                        if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                        else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
                    });
                });
                
                //select/deselect a row when the checkbox is checked/unchecked
                $('#simple-table').on('click', 'td input[type=checkbox]' , function(){
                    var $row = $(this).closest('tr');
                    if($row.is('.detail-row ')) return;
                    if(this.checked) $row.addClass(active_class);
                    else $row.removeClass(active_class);
                });
            
                
            
                /********************************/
                //add tooltip for small view action buttons in dropdown menu
                $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
                
                //tooltip placement on right or left
                function tooltip_placement(context, source) {
                    var $source = $(source);
                    var $parent = $source.closest('table')
                    var off1 = $parent.offset();
                    var w1 = $parent.width();
            
                    var off2 = $source.offset();
                    //var w2 = $source.width();
            
                    if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                    return 'left';
                }
                
                
                
                
                /***************/
                $('.show-details-btn').on('click', function(e) {
                    e.preventDefault();
                    $(this).closest('tr').next().toggleClass('open');
                    $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                });
                /***************/
                
                
                
                
                
                /**
                //add horizontal scrollbars to a simple table
                $('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
                  {
                    horizontal: true,
                    styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
                    size: 2000,
                    mouseWheelLock: true
                  }
                ).css('padding-top', '12px');
                */
            
            
            })
        </script>
    </body>
</html>


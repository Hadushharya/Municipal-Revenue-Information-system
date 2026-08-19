<?php 
include('setting/header.php'); 
include('../db/connection.php'); 

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if required session variables exist
if (isset($_SESSION['SESS_ID'], $_SESSION['SESS_USER_NAME'])) {
    $userid = $_SESSION['SESS_ID'];
    $username = $_SESSION['SESS_USER_NAME'];
} else {
    // Redirect to login page if session variables are missing
    header("Location: ../login.php");
    exit;
}

// Include activity log after validating session
include("../logactivity.php");
?>

     <style>
        .output {
            border: 1px solid #ccc; /* Optional: just for visualization */
            padding: 10px;          /* Optional: some padding */
        }
        #ethiopianDate {
            float: right;           /* Float the span to the right */
        }
    </style>
<script>
 const OFFSET = 79372; // Pre-calculated offset for Ethiopian calendar
  const DAY = 1000 * 60 * 60 * 24; // Milliseconds in a day
  const months = "መስከረም,ጥቅምት,ኅዳር,ታኅሣሥ,ጥር,የካቲት,መጋቢት,ሚያዝያ,ግንቦት,ሰኔ,ሐምሌ,ነሐሴ,ጳጉሜ".split(",");

  let GC, EYear, EMonth, EDate, month, day, year;

  // Validate Gregorian date
  function isValid(dt) {
    GC = new Date(dt);
    const yearLen = dt.substr(dt.lastIndexOf("/") + 1).length;
    return yearLen === 4 && GC.getFullYear() >= 1753;
  }

  // Calculate total Ethiopian days since the offset
  function getECDays(dt) {
    const UTCVal = Date.UTC(GC.getFullYear(), GC.getMonth(), GC.getDate());
    return OFFSET + UTCVal / DAY;
  }

  // Convert Gregorian date to Ethiopian date
  function ECDate(dt) {
    GC = new Date(dt); // Ensure GC is set for the date
    const days = getECDays(dt);
    let year = 1745;
    const yearsApplied = Math.floor(days / 365.25);
    year += yearsApplied;
    let daysRemaining = days - Math.floor(yearsApplied * 365.25);

    // Adjust for leap year approximation error
    if (year % 4 === 0) {
      daysRemaining--;
    }

    if (daysRemaining === 0) {
      year--;
      month = 13;
      day = 5 + (year % 4 === 3 ? 1 : 0);
    } else {
      month = Math.ceil(daysRemaining / 30);
      day = daysRemaining % 30 === 0 ? 30 : daysRemaining % 30;
    }

    return { day, month, year };
  }

  // Get Ethiopian date in words
  function getInWords() {
    return `${months[month - 1]} ${day}, ${year}`;
  }

  // Display Ethiopian date
  function displayEthiopianDate() {
    const gregDate = new Date(); // Get the current Gregorian date
    const ethDate = ECDate(gregDate); // Convert Gregorian to Ethiopian

    // Update the page with the Ethiopian date
    document.getElementById("ethiopianDate").textContent = `${ethDate.day}/${ethDate.month}/${ethDate.year}`;
    document.getElementById("ethiopianDate1").value = `${ethDate.day}/${ethDate.month}/${ethDate.year}`;
    document.getElementById("ethiopianMonth").textContent = `${ethDate.month}`;
    document.getElementById("ethiopianMonth1").value = `${ethDate.month}`;
	 document.getElementById("ethiopianDay").textContent = `${ethDate.day}`;
    document.getElementById("ethiopianDay1").value = `${ethDate.day}`;
  }

  // Display Ethiopian date in a specific section
  function getToday() {
    const todayElement = document.getElementById("today");
    const ethDate = ECDate(new Date());
    todayElement.innerHTML = `${ethDate.day}/${ethDate.month}/${ethDate.year} ${getInWords()} E.C.<br/>`;
  }

  // Display Ethiopian date when the page loads
  window.onload = displayEthiopianDate;
</script>
	<body onload="displayEthiopianDate()" class="no-skin">
		<?php include('setting/headernav1.php'); ?>
				
        

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>
                 <?php //include('setting/headernav2.php'); ?>
				<!-- /.sidebar-shortcuts -->
				
				 
             <?php include('setting/menu.php');
			 
			//sinclude('../db/log_in.php');
             ?>                  
			<!-- /.nav-list -->

		      <!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			<div class="main-content">
			

					<div class="page-content">
	                   <?php include('setting/settingpage.php'); ?>
					  <!-- /.ace-settings-container -->

  					 <div class="col-xs-12">
								
										<div class="col-xs-12">
									<h4 class="lighter">
									<?php
    $i = 0;

   
    $id1 = filter_var($_SESSION['SESS_ID'], FILTER_VALIDATE_INT);
    //$user = $_SESSION['SESS_USER_NAME'];

    // Prepare and execute the query to get user data
    $q = "SELECT * FROM `users` WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $id1); // 'i' means the parameter is an integer
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $fname = $row['fname'];
                $mname = $row['mname'];
                $lname = $row['lname'];
                $userfullname = $fname . ' ' . $mname . ' ' . $lname;
            }
        }
        mysqli_stmt_close($stmt);
    }


function calculateEthiopianYearDifference1() {
    // Get the current Gregorian date
    $gregorianDate = new DateTime();
    $currentYear = (int)$gregorianDate->format('Y');

    // Determine if the current year is a Gregorian leap year
    $isLeapYear = (($currentYear % 4 == 0 && $currentYear % 100 != 0) || ($currentYear % 400 == 0));

    // Ethiopian New Year in Gregorian calendar
    $ethiopianNewYear = new DateTime("$currentYear-09-" . ($isLeapYear ? "12" : "11"));

    // Calculate the Ethiopian year
    // Ethiopian calendar is 8 years behind Gregorian calendar until the Ethiopian New Year
    $ethiopianYear = $currentYear - 8;

    // Check if the Gregorian date is before or after the Ethiopian New Year
    if ($gregorianDate >= $ethiopianNewYear) {
        // After or on Ethiopian New Year, it's the next Ethiopian year
        $ethiopianYear += 1;
    }

    return $ethiopianYear;
}

// Example usage
$ethiopianYear1 = calculateEthiopianYearDifference1();
 $currentyear2=$ethiopianYear1;


// Sanitize 'id' from URL and validate as integer
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    // Invalid ID, redirect or handle error
    die("Invalid client ID.");
}

$tariffamount = 0;
$forpayment_status = 'approved';

// Prepare and execute the query for clientslanddata table
$q = "SELECT * FROM `clientslanddata` WHERE forpayment_status = ? AND id = ?";
if ($stmt = mysqli_prepare($conn, $q)) {
    mysqli_stmt_bind_param($stmt, "si", $forpayment_status, $id); // 's' = string, 'i' = integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $clientid = $row['id'];
        $fullname = htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8');
        $kebele = htmlspecialchars($row['kebele'], ENT_QUOTES, 'UTF-8');
        $block = htmlspecialchars($row['block'], ENT_QUOTES, 'UTF-8');
        $east = htmlspecialchars($row['east'], ENT_QUOTES, 'UTF-8');
        $west = htmlspecialchars($row['west'], ENT_QUOTES, 'UTF-8');
        $north = htmlspecialchars($row['north'], ENT_QUOTES, 'UTF-8');
        $south = htmlspecialchars($row['south'], ENT_QUOTES, 'UTF-8');
        $filenumber = htmlspecialchars($row['filenumber'], ENT_QUOTES, 'UTF-8');
        $levelofplace = htmlspecialchars($row['levelofplace'], ENT_QUOTES, 'UTF-8');
        $occupiedyear = htmlspecialchars($row['occupiedyear'], ENT_QUOTES, 'UTF-8');
        $mainservice = htmlspecialchars($row['mainservice'], ENT_QUOTES, 'UTF-8');
        $kindofvolumeofland = htmlspecialchars($row['kindofvolumeofland'], ENT_QUOTES, 'UTF-8');
        $area = (float)$row['area'];
        $beginofconstruction = htmlspecialchars($row['beginofconstruction'], ENT_QUOTES, 'UTF-8');
        $endofconstruction = htmlspecialchars($row['endofconstruction'], ENT_QUOTES, 'UTF-8');
        $beginpayment = (int)$row['beginpayment'];
        $endpayment = (int)$row['endpayment'];
        $amountofpay = (float)$row['amountofpay'];

        if ($kindofvolumeofland === 'ሊዝ') {
            $amountofpay = (float)$row['amountofpay'];
            $unpaidyear = $endpayment - $beginpayment;
        } else if ($kindofvolumeofland === 'ክራይ') {
            // Query kiraytariff table safely
            $qqkiray = "SELECT DISTINCT levelofplace, tid, minarea, maxarea, tariffamount 
                        FROM `kiraytariff` 
                        WHERE levelofplace = ?";

            if ($stmtki = mysqli_prepare($conn, $qqkiray)) {
                mysqli_stmt_bind_param($stmtki, "s", $levelofplace);
                mysqli_stmt_execute($stmtki);
                $resultki = mysqli_stmt_get_result($stmtki);

                $tariffamount = 0; // Default value
                while ($row1ki = mysqli_fetch_assoc($resultki)) {
                    $tid = (int)$row1ki["tid"];
                    $levelofplace1 = htmlspecialchars($row1ki["levelofplace"], ENT_QUOTES, 'UTF-8');
                    $minarea = (float)$row1ki["minarea"];
                    $maxarea = (float)$row1ki["maxarea"];
                    $ta = (float)$row1ki["tariffamount"];

                    // Check if area is within range
                    if ($minarea <= $area && $maxarea >= $area && $levelofplace === $levelofplace1) {
                        $tariffamount = $ta;
                        break; // exit loop on first match
                    }
                }

    


					

					mysqli_stmt_close($stmtki);
				}

				$amountofpay = $area * $tariffamount;
				
        $unpaidyear = $currentyear2 - $beginpayment;
            }
        }
       
?>
							
 <?php
 
if (isset($_POST['save'])) {
   
$paymentDate = filter_input(INPUT_POST, 'ethiopianDate1', FILTER_SANITIZE_STRING);
    $paymentType = filter_input(INPUT_POST, 'paymentType', FILTER_SANITIZE_STRING);

    $ethiopianDate1     = filter_input(INPUT_POST, 'ethiopianDate1', FILTER_SANITIZE_STRING);
    $ethiopianMonth1    = filter_input(INPUT_POST, 'ethiopianMonth1', FILTER_VALIDATE_INT);
	$EthiopianDate    = filter_input(INPUT_POST, 'ethiopianDay1', FILTER_VALIDATE_INT);
    $fullname1          = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
    $year               = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $filenumber         = filter_input(INPUT_POST, 'filenumber', FILTER_SANITIZE_STRING);
    $levelofplace       = filter_input(INPUT_POST, 'levelofplace', FILTER_SANITIZE_STRING);
    $amountofpay        = filter_input(INPUT_POST, 'amountofpay', FILTER_VALIDATE_FLOAT);
    $paymentyear        = filter_input(INPUT_POST, 'paymentyear', FILTER_VALIDATE_INT);
    $unpaidyear         = filter_input(INPUT_POST, 'unpaidyear', FILTER_VALIDATE_INT);
    $kindofvolumeofland = filter_input(INPUT_POST, 'kindofvolumeofland', FILTER_SANITIZE_STRING);
    $maincash           = filter_input(INPUT_POST, 'maincash', FILTER_VALIDATE_FLOAT);
    $cleanvalue1        = filter_input(INPUT_POST, 'clean', FILTER_VALIDATE_FLOAT);
    $mainservice        = filter_input(INPUT_POST, 'mainservice', FILTER_SANITIZE_STRING);
    $rooms              = filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT);
    $homerentpayment    = filter_input(INPUT_POST, 'homerentpayment', FILTER_VALIDATE_FLOAT);
	  
    // Determine the Ethiopian month
    $ethiopianMonths = [
        1 => 'መስከረም',
        2 => 'ጥቅምቲ',
        3 => 'ሕዳር',
        4 => 'ታህሳስ',
        5 => 'ጥሪ',
        6 => 'ለካቲት',
        7 => 'መጋቢት',
        8 => 'ማያዝያ',
        9 => 'ግንቦት',
        10 => 'ሰነ',
        11 => 'ሓምለ',
        12 => 'ነሓሰ'
    ];
    $month2 = $ethiopianMonths[$ethiopianMonth1] ?? 'ዻጉሜን';

    // Prepared statement for fetching the bank interest and punishment value
    $qqbank = "SELECT bankinterest, punishmentvalue FROM `month` WHERE nameofmonth = ? AND kindofvolumeofland = ?";
    if ($stmt = mysqli_prepare($conn, $qqbank)) {
        mysqli_stmt_bind_param($stmt, "ss", $month2, $kindofvolumeofland);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $bankinterest, $punishmentvalue);

        if (mysqli_stmt_fetch($stmt)) {
            $bankinterest = (float)$bankinterest;
            $punishmentvalue = (float)$punishmentvalue;
        } else {
            $bankinterest = 0;
            $punishmentvalue = 0;
        }

        mysqli_stmt_close($stmt);
    }


	function calculateEthiopianYear() {
    // Get the current Gregorian date
    $gregorianDate = new DateTime();
    $currentYear = (int)$gregorianDate->format('Y');

    // Determine if the current year is a Gregorian leap year
    $isLeapYear = (($currentYear % 4 == 0 && $currentYear % 100 != 0) || ($currentYear % 400 == 0));

    // Ethiopian New Year in Gregorian calendar
    $ethiopianNewYear = new DateTime("$currentYear-09-" . ($isLeapYear ? "12" : "11"));

    // Calculate the Ethiopian year
    // Ethiopian calendar is 8 years behind Gregorian calendar until the Ethiopian New Year
    $ethiopianYear = $currentYear - 8;

    // Check if the Gregorian date is before or after the Ethiopian New Year
    if ($gregorianDate >= $ethiopianNewYear) {
        // After or on Ethiopian New Year, it's the next Ethiopian year
        $ethiopianYear += 1;
    }

    return $ethiopianYear;
}
// Example usage
$ethiopianYearw = calculateEthiopianYear();
 $currentyear2 = $ethiopianYearw;
 // echo $currentyear2;
 $nocount=$currentyear2-$year;
 if  ($year==$currentyear2){
	 if ($month2=='ሓምለ'){
				if ($kindofvolumeofland=='ክራይ'){
					//$punishmentvalue=($punishmentvalue+22);
					$punishment = ((($amountofpay+$cleanvalue1)* $punishmentvalue)/100);
					$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
				}
				else{
					$bankinterest1=(($amountofpay*$bankinterest)/100);
					//$bankinterest = $bankinterest1;
					$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((365+$EthiopianDate)*0.000863));
						}
					  $diffrence = $amountofpay - $bankinterest1;
					$differencewithpunishment = $amountofpay - $punishment;
					$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			     }
	elseif ($month2=='ነሓሰ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+24);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
		}
		else{
			$bankinterest1=(($amountofpay*$bankinterest)/100);
           //$bankinterest = $bankinterest1;
			$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((395+$EthiopianDate)*0.000863));
		}
    $diffrence = $amountofpay - $bankinterest1;
    $differencewithpunishment = $amountofpay - $punishment;
    $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
	}
	elseif ($month2=='መስከረም'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+26);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
		}
		else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((65+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ጥቅምቲ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+28);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((95+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ሕዳር'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+25);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((125+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ታህሳስ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+27);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = ($amountofpay +$cleanvalue1)* (0*($EthiopianDate*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((155+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ጥሪ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+5);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* (($EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((185+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ለካቲት'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+7);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((30+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((215+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='መጋቢት'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+9);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((60+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((245+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}

	 elseif ($month2=='ማያዝያ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+13);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((90+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((275+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ግንቦት'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+15);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((120+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((305+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	elseif ($month2=='ሰነ'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+17);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((150+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=(($amountofpay*$bankinterest)/100);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((335+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	else{
		if ($month2=='ዻጉሜን'){
		if ($kindofvolumeofland=='ክራይ'){
			//$punishmentvalue=($punishmentvalue+17);
			$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((210+$EthiopianDate)*0.000863));
			}
			else{
				$bankinterest1=($amountofpay*$bankinterest);
			   //$bankinterest = $bankinterest1;
				$punishment = ((((($amountofpay*$bankinterest)/100)+$amountofpay +$cleanvalue1))* ((425+$EthiopianDate)*0.000863));
			}
			$diffrence = $amountofpay - $bankinterest1;
			$differencewithpunishment = $amountofpay - $punishment;
			$total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
			}
	     }
	/* else{
    $bankinterest = (($amountofpay +$cleanvalue1)* $bankinterest) / 100;
	    $diffrence = $amountofpay - $bankinterest;
   $punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue) / 100;
    $differencewithpunishment = $amountofpay - $punishment;
    $total = $amountofpay + $bankinterest + $punishment + $cleanvalue1;
	} */
 }
 else if($year>$currentyear2){
	 
	 if ($kindofvolumeofland=='ክራይ'){
			$bankinterest1 = (($amountofpay +$cleanvalue1)* ((0*$EthiopianDate)*0.000863));
		}
		else{
			$bankinterest1=(($amountofpay*$bankinterest)/100);
		}
    $diffrence = $amountofpay - $bankinterest1;
   $punishment = (($amountofpay +$cleanvalue1)* 0) / 100;
    $differencewithpunishment = $amountofpay - $punishment;
	
    $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
 }
 
 elseif($year<$currentyear2){
	 
	  switch ($ethiopianMonth1) { 
        case 1: $month2 = 'መስከረም';
		 $nocount2=($currentyear2-$year);
		    $nocount2=$nocount2+1;
		   // echo $nocount2;
		 	if($kindofvolumeofland=='ክራይ'){
					$punishmentvalue1=($punishmentvalue+21)*($nocount2-1);
					$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
					$bankinterest1 = ((($amountofpay+$cleanvalue1)*($nocount2-1))* (((($nocount2-2)*365)+245+$EthiopianDate)*0.000863));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
				}
				else{
					$bankinterest1=(($amountofpay*$bankinterest)/100);
                   //$bankinterest = $bankinterest1;
	     
					$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+65+$EthiopianDate)*0.000863));
				}
				 $diffrence = $amountofpay - $bankinterest1;
              $differencewithpunishment = $amountofpay - $punishment;
               $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
		     break;
        case 2: $month2 = 'ጥቅምቲ'; 
		     $nocount2=($currentyear2-$year);
		    $nocount2=$nocount2+1;
		      	if($kindofvolumeofland=='ክራይ'){
					$punishmentvalue1=($punishmentvalue+23)*($nocount2-1);
					$punishment = (($amountofpay+$cleanvalue1)* $punishmentvalue1) / 100;
					$bankinterest1 = (($amountofpay+$cleanvalue1)* (((($nocount2-1)*365)+275+$EthiopianDate)*0.000863));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
				}
				else{
					$bankinterest1=(($amountofpay*$bankinterest)/100);
                   //echo $bankinterest1 = $bankinterest1;
	     
					$punishment = ((((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1)))*(((($nocount2-1)*365)+95+$EthiopianDate)*0.000863));
				}
				 $diffrence = $amountofpay - $bankinterest1;
              $differencewithpunishment = $amountofpay - $punishment;
               $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
		     break;
        case 3:
    $month2 = 'ሕዳር';
    $nocount2 = ($currentyear2 - $year);
    $nocount2 = $nocount2 + 1;

    if ($kindofvolumeofland == 'ክራይ') {

        $punishmentvalue1 = ($punishmentvalue + 25) * ($nocount2 - 1);
        $punishment = (($amountofpay + $cleanvalue1) * $punishmentvalue1) / 100;

        $bankinterest1 =
            (($amountofpay + $cleanvalue1) *
            (((($nocount2 - 1) * 365) + 305 + $EthiopianDate) * 0.000863));

        if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }

    } else {

        $bankinterest1 = (($amountofpay * $bankinterest) / 100);

        $punishment =
            (((($amountofpay * $bankinterest) / 100) +
            ($amountofpay + $cleanvalue1)) *
            (((($nocount2 - 1) * 365) + 125 + $EthiopianDate) * 0.000863));
    }

    $diffrence = $amountofpay - $bankinterest1;
    $differencewithpunishment = $amountofpay - $punishment;
    $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;

    break;
        case 4: $month2 = 'ታህሳስ'; 
		       $nocount2=($currentyear2-$year);
			
		       $nocount2=$nocount2+1;
				if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+27)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+335+$EthiopianDate)*0.000863));
			if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
					 
								$punishment = ((((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1)))*(((($nocount2-1)*365)+155+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 5: $month2 = 'ጥሪ'; 
		     $nocount2=($currentyear2-$year);
		      $nocount2=$nocount2+1;
               if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+29)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+($EthiopianDate)*0.000863)));
		 if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
					 
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+185+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 6: $month2 = 'ለካቲት'; 
		      $nocount2=($currentyear2-$year);
		      $nocount2=$nocount2+1;
		      if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+31)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = (($amountofpay +$cleanvalue1)* (((($nocount2-1)*365)+(30+$EthiopianDate)*0.000863)));
			if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
					 
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+215+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 7: $month2 = 'መጋቢት'; 
		     $nocount2=($currentyear2-$year);
		      $nocount2=$nocount2+1;
                      if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+33)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(60+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
					 
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+245+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 8: $month2 = 'ማያዝያ'; 
		      $nocount2=($currentyear2-$year);
		    $nocount2=$nocount2+1;
		           if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+35)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(90+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+275+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 9: $month2 = 'ግንቦት'; 
		         $nocount2=($currentyear2-$year);
		         $nocount2=$nocount2+1;
		      if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+37)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(120+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+305+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 10: $month2 = 'ሰነ'; 
		          $nocount2=($currentyear2-$year);
		        $nocount2=$nocount2+1;
		     if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+39)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(150+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
					 
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+335+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        case 11: $month2 = 'ሓምለ'; 
		$nocount1=($currentyear2-$year);
		$nocount1=$nocount1+1;
		  if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+41)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = (($amountofpay +$cleanvalue1)* (((($nocount2-1)*365)+(180+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+30+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;     
		case 12: $month2 = 'ነሓሰ';
		    $nocount2=($currentyear2-$year);
			
		    $nocount2=$nocount2+1;
		      if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+43)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(210+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+60+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
        default: $month2 = 'ዻጉሜን'; 
		$nocount3=($currentyear2-$year);
		$nocount3=$nocount3+1;
		 if($kindofvolumeofland=='ክራይ'){
								$punishmentvalue1=($punishmentvalue+43)*($nocount2-1);
								$punishment = (($amountofpay +$cleanvalue1)* $punishmentvalue1) / 100;
								$bankinterest1 = ((($amountofpay +$cleanvalue1))* (((($nocount2-1)*365)+(185+$EthiopianDate)*0.000863)));
		if ($bankinterest1 > ($amountofpay + $cleanvalue1)) {
            $bankinterest1 = ($amountofpay + $cleanvalue1);
            ($punishment > ($amountofpay + $cleanvalue1));
            $punishment = ($amountofpay + $cleanvalue1);
        }
							}
							else{
								$bankinterest1=(($amountofpay*$bankinterest)/100);
							   //$bankinterest = $bankinterest1;
								$punishment = (((($amountofpay*$bankinterest)/100)+($amountofpay +$cleanvalue1))*(((($nocount2-1)*365)+65+$EthiopianDate)*0.000863));
							}
							 $diffrence = $amountofpay - $bankinterest1;
						  $differencewithpunishment = $amountofpay - $punishment;
						   $total = $amountofpay + $bankinterest1 + $punishment + $cleanvalue1;
						 break;
		break;
    }
	
 }
 
 
 

    // Get the current year
    $currentyear = $ethiopianDate1; 
      $grandtotal=$total+$homerentpayment;
    // Prepared statement for inserting into clientslanddatataxdecides
				
						$queryLand = "INSERT INTO `clientslanddatataxdecides` 
							(`id`, `clientid`,`userid`, `fullname`, `year`, `filenumber`, `levelofplace`, `kindofvolumeofland`,`amountofpay`, 
							`paymentyear`,`unpaidyear`,  `maincash`, `bankinterest`, `diffrence`, `punishment`, 
							`differencewithpunishment`,`mainservice`, `clean`, `total`, `forpayment_status`,
							`decidedby`, `registeredtime`, `forcashierstatus`) 
							VALUES (NULL, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?,?, ?, ?, 'notapproved', ?, ?, 'notapproved')";

						if ($stmt = mysqli_prepare($conn, $queryLand)) {
							mysqli_stmt_bind_param($stmt, "iisssssdsidddddsidss", 
								$clientid, $userid, $fullname1, $year, $filenumber, $levelofplace,
								$kindofvolumeofland, $amountofpay, $paymentyear, $unpaidyear, $maincash, 
								$bankinterest1, $diffrence, $punishment, $differencewithpunishment, 
								$mainservice, $cleanvalue1,  $total, $userfullname, $paymentDate);
							mysqli_stmt_execute($stmt);
							echo '<p class="success" style="color:#390">ግብሪ መሬት ብትክክል ተወሲንሎም ኣሎ!</p>';
							mysqli_stmt_close($stmt);
							$ip = $_SERVER['REMOTE_ADDR'];
			$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
		   insertLog($conn, $userid, $username, "decided", "Land Tax is decided correctly.", "success", $ip, $agent); 
						}
					}
						// Insert for home rent
						// Fetch client ID from URL safely
					
			
   
}
      


    

	


							

// ======= ETHIOPIAN YEAR CALCULATION FUNCTION =========
function calculateEthiopianYearDifference() {
    $gregorianDate = new DateTime();
    $currentYear = (int)$gregorianDate->format('Y');
    $isLeapYear = (($currentYear % 4 == 0 && $currentYear % 100 != 0) || ($currentYear % 400 == 0));
    $ethiopianNewYear = new DateTime("$currentYear-09-" . ($isLeapYear ? "12" : "11"));
    $ethiopianYear = $currentYear - 8;
    if ($gregorianDate >= $ethiopianNewYear) {
        $ethiopianYear += 1;
    }
    return $ethiopianYear;
}

$ethiopianYear = calculateEthiopianYearDifference();
$clientid = $_GET['id'] ?? 0;
?>

<form class="form-horizontal" name="dateForm" role="form" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>ግብሪ ምዉሳን </legend>

        
        <div class="form-group">
            <span id="ethiopianDate" type="hidden" align="right"></span>
            <p align='right'>ዕለት:</p>
            <input id="ethiopianDate1" type="hidden" readonly name="ethiopianDate1" required />
        </div>
        <div class="form-group">
            <span id="ethiopianMonth" type="hidden" align="right"></span>
            <input id="ethiopianMonth1" type="hidden" readonly name="ethiopianMonth1" required />
        </div>
		 <div class="form-group">
            <span id="ethiopianDay" type="hidden" align="right"></span>
            <input id="ethiopianDay1" type="hidden" readonly name="ethiopianDay1" required />
        </div>

            <?php
            $i=0;
            $currentyear1=$ethiopianYear;
            $qyeara = "SELECT *, max(year) AS maxyear1, forpayment_status, forcashierstatus 
                       FROM `clientslanddatataxdecides`  
                       WHERE clientid = ? AND fullname = ? AND filenumber = ? 
                       ORDER BY id DESC";
            if ($stmt = mysqli_prepare($conn, $qyeara)) {
                mysqli_stmt_bind_param($stmt, "iss", $clientid, $fullname, $filenumber);
                mysqli_stmt_execute($stmt);
                $resultyearra = mysqli_stmt_get_result($stmt);
                while ($rowyearra = mysqli_fetch_array($resultyearra)) {
                    $maxyear = $rowyearra['maxyear1'];
                    $forpayment_status = $rowyearra['forpayment_status'];
                    $forcashierstatus = $rowyearra['forcashierstatus'];
                    if ($maxyear == NULL && $forpayment_status == NULL && $forcashierstatus == NULL) {
                        $maxyear = $beginpayment - 1;
                    } else if ($maxyear > 0 && $forpayment_status == 'approved' && $forcashierstatus == 'notapproved') {
                        $maxyear = $maxyear;
                    } else {
                        $maxyear = $maxyear;
                    }
                    $currentyear3 =$currentyear1 +1;
                    $remainingyear = ($kindofvolumeofland == 'ሊዝ') ? ($endpayment - $maxyear) : ($currentyear3 - $maxyear);
                }
            }
            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="year">ግብሪ ዓመት(ዓ/ም): </label>
                <select class="col-xs-9 col-sm-2" name="year" id="year" required>
                    <option value="">ዓመት ምረፅ</option>
                    <?php 
                    $i = 0;
                    while ($i < $remainingyear) {
                        $maxyear = (int)$maxyear + 1;
                        echo '<option value="' . htmlspecialchars($maxyear, ENT_QUOTES, 'UTF-8') . '">'
                             . htmlspecialchars($maxyear, ENT_QUOTES, 'UTF-8') . '</option>';
                        $i++;
                    } 
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="fullname">ሙሉእ ሽም: </label>
                <div class="col-sm-10">
                    <input type="text" id="fullname" readonly name="fullname" required 
                           value="<?php echo htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'); ?>" 
                           placeholder="ሙሉእ ሽም" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="filenumber">ፋ/ቁፅሪ</label>
                <div class="col-sm-10">
                    <input type="text" id="filenumber" readonly name="filenumber" 
                           value="<?php
                           $q = "SELECT * FROM `clientslanddata` WHERE fullname = ? AND id = ? AND filenumber = ?";
                           if ($stmt = mysqli_prepare($conn, $q)) {
                               mysqli_stmt_bind_param($stmt, "sis", $fullname, $id, $filenumber);
                               mysqli_stmt_execute($stmt);
                               $result = mysqli_stmt_get_result($stmt);
                               if ($row = mysqli_fetch_assoc($result)) {
                                   echo htmlspecialchars($row['filenumber'], ENT_QUOTES, 'UTF-8');
                               }
                               mysqli_stmt_close($stmt);
                           }
                           ?>" required placeholder="ፋ/ቁፅሪ" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <?php
            $qp = "SELECT * FROM `clientslanddata` WHERE fullname = ? AND id = ? AND filenumber = ?";
            if ($stmtp = mysqli_prepare($conn, $qp)) {
                mysqli_stmt_bind_param($stmtp, 'sis', $fullname, $id, $filenumber);
                mysqli_stmt_execute($stmtp);
                $resultp = mysqli_stmt_get_result($stmtp);

                if ($rowp = mysqli_fetch_assoc($resultp)) {
                    $kindofvolumeofland = $rowp['kindofvolumeofland'];
                    $levelofplace = $rowp['levelofplace'];
                    $area = $rowp['area'];
                    $mainservice = $rowp['mainservice'];
                }
                mysqli_stmt_close($stmtp);
            }
            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="levelofplace">ደረጃ ቦታ :</label>
                <div class="col-sm-10">
                    <input type="text" id="levelofplace" name="levelofplace"
                           value="<?php echo htmlspecialchars($levelofplace ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="kindofvolumeofland">ናይቲ መሬት ዓይነት ትሕዝቶ:</label>
                <div class="col-sm-10">
                    <input type="text" id="kindofvolumeofland" name="kindofvolumeofland"
                           value="<?php echo htmlspecialchars($kindofvolumeofland ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="area">ስፍሓት መሬት (ብካሬ):</label>
                <div class="col-sm-10">
                    <input type="text" id="area" name="area"
                           value="<?php echo htmlspecialchars($area ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="ስፍሓት መሬት" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="amountofpay">ዝከፍሎ መጠን ገንዘብ:</label>
                <div class="col-sm-10">
                    <input type="text" id="amountofpay" name="amountofpay"
                           value="<?php echo htmlspecialchars($amountofpay ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="ዝከፍሎ መጠን ገንዘብ" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="paymentyear">ዝክፈለሉ እዋን:</label>
                <div class="col-sm-10">
                    <input type="text" id="paymentyear" name="paymentyear"
                           value="<?php echo htmlspecialchars($endpayment ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="ዝክፈለሉ እዋን" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="unpaidyear">ዘይከፈለሉ እዋን:</label>
                <div class="col-sm-10">
                    <input type="text" id="unpaidyear" name="unpaidyear"
                           value="<?php echo htmlspecialchars($unpaidyear ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="ዘይከፈለሉ እዋን" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="maincash">ዋና ገንዘብ:</label>
                <div class="col-sm-10">
                    <input type="text" id="maincash" name="maincash"
                           value="<?php echo htmlspecialchars(($amountofpay ?? 0) * ($unpaidyear ?? 0), ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="ዋና ገንዘብ" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="mainservice">ዓ/ግልጋሎት:</label>
                <div class="col-sm-10">
                    <input type="text" id="mainservice" name="mainservice"
                           value="<?php echo htmlspecialchars($mainservice ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           readonly required placeholder="" class="col-xs-10 col-sm-5" />
                </div>
            </div>

            <?php  
            $cleanvalue1 = ""; 
            $q = "SELECT * FROM `rule` WHERE mainservice = ?";
            $stmt = mysqli_prepare($conn, $q);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $mainservice);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row1 = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $mainservice = $row1["mainservice"]; 
                    $cleanvalue1 = $row1["value"]; 
                }
                mysqli_stmt_close($stmt);
            }
            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right" for="clean">ናይ ፅሬት: </label>
                <div class="col-sm-10">
                    <input type="text" id="clean" name="clean" 
                           value="<?php echo htmlspecialchars($cleanvalue1, ENT_QUOTES, 'UTF-8'); ?>" 
                           readonly required placeholder="ናይ ፅሬት" 
                           class="col-xs-10 col-sm-5" />
                </div>
            </div>
       

        <!-- SAVE / RESET -->
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <center>
                    <p>
                        <button type="submit" name="save" class="btn btn-white btn-info btn-bold">
                            <i class="ace-icon fa fa-plus bigger-120 orange"></i> ወስን
                        </button> &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="reset" class="btn btn-white btn-default btn-round">
                            <i class="ace-icon fa fa-times red2"></i> ኣፅሪ
                        </button>
                    </p>
                </center>
            </div>
        </div>
    </fieldset>
</form>

<script>
function togglePayment() {
    var type = document.querySelector('input[name="paymentType"]:checked').value;
    document.getElementById('landSection').style.display = (type === 'land' || type === 'both') ? 'block' : 'none';
    document.getElementById('homeSection').style.display = (type === 'both') ? 'block' : 'none';
}
</script>


								</h4>
										 
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group"> 
					
				</div>
				
				<center><h3>ዝርዝር ተጠቀምቲ መሬት  </h3></center>
				
				
				
					<div id="dt">
										  <thead> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										</thead> 
										<div  class="table-header">
											Results 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div id="tablecon"> 
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
													<tr>
														<th class="center">
															<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														
														<th class='blue'>ሙሉእ ሽም</th>
														<th class='blue'> ግብሪ ዓመት(ዓ/ም)</th>
														<th class='blue'>ፋ/ቁፅሪ</th>
														<th class='blue'>ዝከፍሎ መጠን ገንዘብ</th>
														
														<th class='blue'>ዘይከፈለሉ እዋን</th>
														
														
														<th class='blue'> ባንኪ ወለድ	</th>	
                                                        <th class='blue'> ኣፈላላይ</th>
														<th class='blue'> ቅፅዓት</th>
														<th class='blue'> ኣፈላላይ</th>
                                                        <th class='blue'> ናይ ፅሬት	</th>
                                                          <th class='blue'> ድምር  ግብሪ	</th>
														   
													 <th  rowspan='3' class="blue"> ባዓል ሞያ   </th>
												     <th class='blue' colspan='2'>ተግብር</th>
													</tr>
												</thead>

										
												<tbody>
<?php
$i = 0;



// Aggregate land tax and join pre-aggregated home rent
$q = "
SELECT 
    l.clientid,
    l.registeredtime,
    SUM(l.amountofpay) AS amountofpay,
    GROUP_CONCAT(DISTINCT l.paymentyear ORDER BY l.paymentyear ASC SEPARATOR ', ') AS paymentyear,
    SUM(l.unpaidyear) AS unpaidyear,
    SUM(l.maincash) AS maincash,
    SUM(l.bankinterest) AS bankinterest,
    SUM(l.diffrence) AS diffrence,
    SUM(l.punishment) AS punishment,
    SUM(l.differencewithpunishment) AS differencewithpunishment,
    GROUP_CONCAT(DISTINCT l.year ORDER BY l.year ASC SEPARATOR ', ') AS years,
    MIN(l.id) AS id,
    l.mainservice,
    SUM(l.clean) AS clean,
    SUM(l.total) AS total,
    l.decidedby,
    l.forpayment_status,
    l.forcashierstatus,
    l.paidtime,
    l.fullname,
    l.filenumber,
    l.levelofplace,
    l.kindofvolumeofland
FROM clientslanddatataxdecides l
WHERE l.clientid = ? AND l.fullname = ? AND l.filenumber = ?
GROUP BY l.clientid, l.registeredtime
ORDER BY l.registeredtime DESC
";


$stmt = mysqli_prepare($conn, $q);
mysqli_stmt_bind_param($stmt, "ssi", $clientid, $fullname, $filenumber);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($rowc = mysqli_fetch_assoc($result)) {
    $i++;
?>
<tr>
    <td class="center">
        <label class="pos-rel">
            <?php echo htmlspecialchars($i); ?>
            <span class="lbl"></span>
        </label>
    </td>

    <td class="hidden-480"><?php echo htmlspecialchars($rowc['fullname']); ?></td>
    <td><?php echo htmlspecialchars($rowc['years']); ?></td>
    <td><?php echo htmlspecialchars($rowc['filenumber']); ?></td>

    <!-- Land tax fields -->
    <td><?php echo number_format($rowc['amountofpay'], 2); ?></td>
    <td><?php echo htmlspecialchars($rowc['paymentyear']); ?></td>
    <td><?php echo number_format($rowc['bankinterest'], 2); ?></td>
    <td><?php echo number_format($rowc['diffrence'], 2); ?></td>
    <td><?php echo number_format($rowc['punishment'], 2); ?></td>
    <td><?php echo number_format($rowc['differencewithpunishment'], 2); ?></td>

    <!-- Clean value -->
    <td><?php echo number_format($rowc['clean'], 2); ?></td>
    <td><?php echo number_format($rowc['total'], 2); ?></td>


    <td><?php echo htmlspecialchars($rowc['decidedby']); ?></td>

    <td>
       <!-- <div class="hidden-sm hidden-xs action-buttons">
            <a class="red" href="delete_clientlanddetail<?php echo '?id='.htmlspecialchars($rowc['id']); ?>" 
               class="tooltip-success" data-rel="tooltip" title="Delete">
                <i class="ace-icon fa fa-trash bigger-130"></i>
            </a> 
        </div> -->
    </td>
</tr>
<?php
}
?>









													<td>
													<?php
$clientid = $_GET['id'];
$Dailytotal = 0;

// 1. Get the latest registeredtime for this client
$stmt = $conn->prepare("SELECT MAX(registeredtime) AS latest_date FROM clientslanddatataxdecides WHERE clientid = ?");
$stmt->bind_param("i", $clientid);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$latest_date = $row['latest_date'];
$stmt->close();

// 2. Sum land tax fields for this client on the latest date
$queryLandSum = "
    SELECT 
        SUM(amountofpay) AS amountofpay,
        SUM(bankinterest) AS bankinterest,
        SUM(punishment) AS punishmenttotal,
        SUM(clean) AS totallean
    FROM clientslanddatataxdecides
    WHERE clientid = ? AND registeredtime = ? AND forpayment_status='notapproved' AND forcashierstatus='notapproved'
";
$stmt = $conn->prepare($queryLandSum);
$stmt->bind_param("is", $clientid, $latest_date);
$stmt->execute();
$sumres = $stmt->get_result();
$rowsum = $sumres->fetch_assoc();
$amountofpay = $rowsum['amountofpay'];
$bankinterest = $rowsum['bankinterest'];
$punishment = $rowsum['punishmenttotal'];
$clean = $rowsum['totallean'];
$stmt->close();


// 4. Calculate total
$Dailytotal = $amountofpay + $bankinterest + $punishment + $clean;



// 5. Display in a single row
echo "<tr style='background-color: #198754; color: white; font-weight: bold;'>
    <td colspan='3' align='right'>
        <strong>ዝከፍሎ መጠን ገንዘብ:</strong> " . number_format($amountofpay, 2) . " ብር
    </td>
    <td colspan='4' align='right'>
        <strong>ባንኪ ወለድ:</strong> " . number_format($bankinterest, 2) . " ብር
    </td>
    <td colspan='2' align='right'>
        <strong>ቅፅዓት:</strong> " . number_format($punishment, 2) . " ብር
    </td>
    <td colspan='1' align='right'>
        <strong>ፅሬት:</strong> " . number_format($clean, 2) . " ብር
    </td>
   
    <td colspan='3' align='left'>
        <strong>ጠ/ድምር:</strong> " . number_format($Dailytotal, 2) . " ብር
    </td>
</tr>";
?>
</td>
													
													</tr> <?php //} ?>
												</tbody>
											</table>
										</div>
										</div>
									</div>
									</form>
					 </div>
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
					   null, null, null,    null, null, null, null, null, null, null, null, null,  
					   null, null, 
					  { "bSortable": false }
					],
					"aaSorting": [],
					
					
					//"bProcessing": true,
			        //"bServerSide": true,
			        //"sAjaxSource": "http://127.0.0.1/table.php"	,
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					"sScrollX": "100%",
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


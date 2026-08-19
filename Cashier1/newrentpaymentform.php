<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Include header and database connection
include('setting/header.php');
include('../db/connection.php');

// Check if user is logged in
if (!isset($_SESSION['SESS_ID']) || !isset($_SESSION['SESS_USER_NAME'])) {
    // Redirect to login if session not set
    header("Location: ../login.php");
    exit;
}

// Store session values safely
$username=$_SESSION['SESS_USER_NAME'];
            $useridc=$_SESSION['SESS_ID'];	

// Include log activity file
include('../logactivity.php');
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
	<body class="no-skin">
		<?php include('setting/headernav1.php'); ?>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>
	
                 <?php //include('setting/headernav2.php'); 
				 //echo $Date;?>
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
			
										
					<div class="page-content" >
	                   <?php include('setting/settingpage.php'); 
					   
					   
					   ?>
								
					  <!-- /.ace-settings-container -->

  					 <div class="col-xs-12">
					 <div >


									<div class="col-xs-12">
									<h4 class="lighter">
	<?php


$id1 = isset($_SESSION['SESS_ID']) ? (int)$_SESSION['SESS_ID'] : 0;
if ($id1 <= 0) {
    // Invalid or missing session ID, redirect or show an error
    die("Invalid session. Please log in.");
}
$stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$stmt->bind_param("i", $id1);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $userfullname = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];
} else {
    die("User not found.");
}

// Accept multiple payment IDs from URL like ?id=1,2,3
$idList = isset($_GET['id']) ? explode(',', $_GET['id']) : [];
$idList = array_map('intval', $idList);
if (empty($idList)) die("No payment IDs specified.");

$homeowner_id = isset($_GET['homeowner_id']) ? (int)$_GET['homeowner_id'] : 0;
if (!$homeowner_id) die("Homeowner ID not specified.");

// Fetch all payments for the given IDs
$placeholders = implode(',', array_fill(0, count($idList), '?'));
$types = str_repeat('i', count($idList));

$stmt = $conn->prepare("
    SELECT h.*, c.* 
    FROM home_rent_payments h
    LEFT JOIN clientslanddatataxdecides c
        ON h.homeowner_id = c.clientid AND h.payment_month=c.year AND c.forpayment_status = 'approved'
    WHERE h.payment_id IN ($placeholders) AND h.status = 'notapproved'
    ORDER BY h.payment_month ASC
");
$stmt->bind_param($types, ...$idList);
$stmt->execute();
$result = $stmt->get_result();

$years = [];
$roomsList = [];
$paymentsList = [];
while ($row = $result->fetch_assoc()) {
    $years[] = $row['payment_month'];
    $roomsList = $row['rooms'];
    $paymentsList = $row['amount_paid'];
}

// Fetch homeRent
$stmt = $conn->prepare("SELECT rooms, room_cost FROM homeowners WHERE homeowner_id = ? LIMIT 1");
$stmt->bind_param("i", $homeowner_id);
$stmt->execute();
$result = $stmt->get_result();
$homeRent = 0;
if ($row = $result->fetch_assoc()) $homeRent = $row['room_cost'];

// Handle form submission
if (isset($_POST['save'])) {
    $paidtime      = $_POST['ethiopianDate1'];
    $paymentby     = $_POST['paymentby'];
    $tid           = $_POST['tid'];
    $receiptnumber = $_POST['receiptnumber'];
    $selectedTable = $_POST['paymentType'] ?? '';

    $conn->begin_transaction();
    try {
        if ($selectedTable === 'home') {
            $status = 'approved';
            $clientid = $homeowner_id;

            $placeholders = implode(',', array_fill(0, count($idList), '?'));
            $stmt_update = $conn->prepare("
                UPDATE home_rent_payments 
                SET payment_method = ?, tin = ?, receipt_no = ?, paiddate = ?, status = ?, paidby=?
                WHERE homeowner_id = ? AND payment_id IN ($placeholders)
            ");
            if (!$stmt_update) die("Prepare failed: " . $conn->error);

            $types = str_repeat('s', 5) . 's' . 'i' . str_repeat('i', count($idList)); // sssssi + ids
            $params = array_merge([$paymentby, $tid, $receiptnumber, $paidtime, $status,$userfullname, $clientid], $idList);

            $refs = [];
            foreach ($params as $key => $value) $refs[$key] = &$params[$key];
            call_user_func_array([$stmt_update, 'bind_param'], array_merge([$types], $refs));

            if (!$stmt_update->execute()) throw new Exception($stmt_update->error);
            $stmt_update->close();
        }

        $conn->commit();
        echo '<p class="success" style="color:#390">ክፍሊት ብትኽክል ተፈፂሙ ኣሎ።</p>';
        echo '<meta content="2;viewrentpayment" http-equiv="refresh" />';

        $ip    = $_SERVER['REMOTE_ADDR'];
                        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                        insertLog($conn, $useridc, $userfullname, "Home Rent Paid", "Paid Successfully.", "success", $ip, $agent);


    } catch (Exception $e) {
        $conn->rollback();
        echo '<p style="color:red">Payment failed: ' . htmlspecialchars($e->getMessage()) . '</p>';

        
    }
}
?>

<form name="theForm" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>ግብሪ ምክፋል</legend>

        <!-- Payment type -->
        <div class="form-group">
            <label class="col-sm-2 control-label no-padding-right">ዓይነት ክፍሊት:</label>
            <div class="col-sm-10">
                <label><input type="radio" name="paymentType" value="home">  ክራይ ክፍሊት</label>
            </div>
        </div>

          <div class="form-group">
                <span id="ethiopianDate" type="hidden" align="right"></span>
                <p align='right'>ዕለት:</p>
                <input id="ethiopianDate1" type="hidden" readonly name="ethiopianDate1" required />
            </div>

        <!-- Single fields with concatenated values -->
        <div class="form-group">
            <label class="col-sm-2 control-label">ግብሪ ዓመት:</label>
            <div class="col-sm-10">
                <input type="text" name="year" value="<?php echo htmlspecialchars(implode(',', $years)); ?>" readonly class="col-xs-10 col-sm-5" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="rooms">በዝሒ ዝካረ ገዛ:</label>
            <div class="col-sm-10">
                <input type="text" name="rooms" value="<?php echo htmlspecialchars($roomsList); ?>" readonly class="col-xs-10 col-sm-5">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="homeRent">ናይ ሓደ ገዛ ክራይ ክፍሊት:</label>
            <div class="col-sm-10">
                <input type="text" name="homeRent" value="<?php echo htmlspecialchars($homeRent); ?>" readonly class="col-xs-10 col-sm-5">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="homerentpayment">ጠቅላላ ናይ ክራይ ክፍሊት:</label>
            <div class="col-sm-10">
                <input type="text" name="homerentpayment" value="<?php echo htmlspecialchars($paymentsList); ?>" readonly class="col-xs-10 col-sm-5">
            </div>
        </div>

        <!-- Hidden payment IDs -->
        <?php foreach ($idList as $pid): ?>
            <input type="hidden" name="payment_ids[]" value="<?php echo $pid; ?>">
        <?php endforeach; ?>

        <!-- Payment method & receipt -->
        <div class="form-group">
            <label class="col-sm-2 control-label no-padding-right" for="paymentby">ክፍሊት ዝፍፀም:</label>
            <div class="col-sm-4">
                <select name="paymentby" id="paymentby" class="form-control col-xs-5 col-sm-3">
                    <option value="">-- ምረፅ --</option>
                    <option value="ጥረ ገንዘብ">ጥረ ገንዘብ</option>
                    <option value="ባንኪ">ባንኪ</option>
                </select>
            </div>
        </div>

        <div class="form-group" id="payment-group" style="display:none;">
            <label class="col-sm-2 control-label no-padding-right" for="tid">መለለይ ባንኪ (TID):</label>
            <div class="col-sm-4">
                <input type="text" id="tid" name="tid" placeholder="መለለይ ባንኪ" class="form-control col-xs-5 col-sm-3" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label no-padding-right" for="receiptnumber">ደረሰይ ቁፅሪ:</label>
            <div class="col-sm-4">
                <input type="text" id="receiptnumber" name="receiptnumber" placeholder="ደረሰይ ቁፅሪ" class="form-control col-xs-5 col-sm-3" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="save" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </div>
        </div>
    </fieldset>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#paymentby').on('change', function () {
        if ($(this).val() === 'ባንኪ') $('#payment-group').slideDown();
        else { $('#payment-group').slideUp(); $('#tid').val(''); }
    });
});
</script>


										
														
										 
								
												
									
									
								</h4>
										</div> 
<div>										
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group"> 
					
				</div>
				
				<center><h3>ሽም ዝርዝር ክፍሊት ዝፈፀሙ ተገልገልቲ  </h3></center>
				
				
				
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
														<th class='blue'>ዝክፈለሉ እዋን</th>
														<th class='blue'>ዘይከፈለሉ እዋን</th>
														<th class='blue'>መዓልቲ </th>
														<th class='blue'>ወርሒ </th>
														<th class='blue'>ዋና ገንዘብ</th>
														<th class='blue'> ባንኪ ወለድ	</th>	
                                                        <th class='blue'> ኣፈላላይ</th>
														<th class='blue'> ቅፅዓት</th>
														<th class='blue'> ኣፈላላይ</th>
                                                        <th class='blue'> ናይ ፅሬት	</th>
                                                          <th class='blue'> ድምር	</th>
														  <th class='blue'> ደረሰይ ቁፅሪ		</th>
														  <th  class="blue"> ሒሳብ ባዓል ሞያ   </th>
													     <th class='blue' colspan='2'>ተግብር</th>
													</tr>
												</thead>

										
												<tbody>
															 <?php
												$i = 0;
												//$cont_id=0;

												//$userid=$_SESSION['SESS_ID'];

												$query1 = "SELECT * FROM `clientslanddatataxdecides` WHERE fullname = ? AND forpayment_status = 'approved' AND forcashierstatus = 'approved'";
												$stmt = $conn->prepare($query1);
												$stmt->bind_param("s", $fullname);
												$stmt->execute();
												$result12 = $stmt->get_result();

												while ($rowcli = $result12->fetch_array()) {
													$id3 = $rowcli['id'];

													$i++;
												?>

													<tr>
														<td class="center">
															<label class="pos-rel">
																<?php echo $i; ?>
																<span class="lbl"></span>
															</label>
														</td>

														
														
														<td class="hidden-480"><?php  $fullname=$rowcli['fullname']; echo $fullname; ?></td>
														<td> <?php	echo $rowcli['year'];	  ?> </td>
														<td> <?php	echo $rowcli['filenumber'];	  ?> </td>
														<td> <?php	echo $rowcli['amountofpay'];	  ?> </td>
														<td> <?php	echo $rowcli['paymentyear'];	  ?> </td>
														<td> <?php echo $rowcli['unpaidyear']; ?> </td>
														<td><?php echo $rowcli['day']; ?></td>
														<td><?php echo $rowcli['month']; ?></td>
														<td><?php echo $rowcli['maincash']; ?></td>
														<td><?php echo $rowcli['bankinterest']; ?></td>
														<td><?php echo $rowcli['diffrence']; ?></td>
														<td><?php echo $rowcli['punishment']; ?></td>
														<td><?php echo $rowcli['differencewithpunishment']; ?></td>
														<td><?php echo $rowcli['clean'];  ?></td>
														<td><?php echo $rowcli['total']; ?></td>
														<td><?php echo $rowcli['receiptnumber']; ?></td>
														<td><?php echo $rowcli['paidby']; ?></td>

														
														<td>
															<div class="hidden-sm hidden-xs action-buttons">
															
																<a class="blue" href="edit_clientlanddetail<?php  echo '?id='.$id3; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																	<i class="ace-icon fa fa-pencil-square-o bigger-130"></i>
																</a> 
															</div>
														</td>
														<td>
															<div class="hidden-sm hidden-xs action-buttons">
																<a class="red" href="delete_listofmaterials<?php  //echo '?full='.$fullname;  ?>" class="tooltip-success" data-rel="tooltip" title="Delete">
																	<i class="ace-icon fa fa-trash bigger-130"></i>
																</a> 
															</div>
														</td>
													</tr>
 
													 <?php } ?>
												</tbody>
											</table>
										</div>
										</div>
									</div>
									</form>
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
					   null, null, null,  null, null, null, null, null, null,  null, null, null,  null, null, null,  null, null, null, 
					  { "bSortable": false }
					],
					"aaSorting": [],
					
					
					//"bProcessing": true,
			        //"bServerSide": true,
			        //"sAjaxSource": "http://127.0.0.1/table.php"	,
			
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


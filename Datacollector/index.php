<?php include('setting/header.php'); ?>
<?php include('../db/connection.php');
if(!isset($_SESSION['SESS_ID'])) {
    die("Session error: User not logged in");
}

$user_id = $_SESSION['SESS_ID'];
//$letter_folders1="Letters";	
function gregorianToEthiopian($date) {
    $date = new DateTime($date);
    $gregorianYear = (int)$date->format('Y');
    $gregorianMonth = (int)$date->format('m');
    $gregorianDay = (int)$date->format('d');
    
    // Calculate Ethiopian date
    $ethiopianYear = $gregorianYear - 8;
    
    // Adjust for Ethiopian New Year (Sept 11/12)
    if ($gregorianMonth >= 9 || ($gregorianMonth == 8 && $gregorianDay >= 11)) {
        $ethiopianYear++;
    }
    
    // Simple day/month conversion (for demonstration)
    $ethiopianMonth = $gregorianMonth - 8;
    if ($ethiopianMonth <= 0) {
        $ethiopianMonth += 12;
    }
    
    $ethiopianDay = $gregorianDay;
    
    return sprintf("%d/%d/%d", $ethiopianDay, $ethiopianMonth, $ethiopianYear);
}

// Get today's Ethiopian date
$ethiopianDateToday = gregorianToEthiopian(date('Y-m-d'));

// Count records for this date
$sql = "SELECT COUNT(*) FROM clientslanddata 
        WHERE registeredtime = ? 
        AND userid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $ethiopianDateToday, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$todayCount = mysqli_fetch_array($result)[0];
?>

<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
	 
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="../assets/css/colorbox.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="../assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="../assets/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		<script>
    // JavaScript to display Ethiopian date (optional)
    function displayEthiopianDate() {
        const ethDate = <?= json_encode($ethiopianDateToday) ?>;
        document.getElementById("ethiopianDateDisplay").textContent = ethDate;
    }
    window.onload = displayEthiopianDate;
    </script>
	</head>
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
                 <?php //include('setting/headernav2.php'); ?>
				<!-- /.sidebar-shortcuts -->
				
				 
             <?php include('setting/menu.php');
			 
			//include('../db/log_in.php');
             ?>
			 
			 	                                  
			<!-- /.nav-list -->

		      <!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			<div class="main-content">


<div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <!-- New Customers Card -->
                                    <div class="col-sm-4">
                                       <div class="infobox infobox-green">
    <div class="infobox-icon">
        <i class="ace-icon fa fa-user-plus"></i>
    </div>
    <div class="infobox-data">
        <span class="infobox-data-number">
           <?php
                require_once('ethiopian_date_converter.php');

                $today_ethiopian_string = gregorian_to_ethiopian_string(date('Y'), date('m'), date('d'));
                $income_query = "SELECT COUNT(*) as daily_income 
                                 FROM `clientslanddata` 
                                 WHERE registeredtime = '$today_ethiopian_string'
                                 AND forpayment_status = 'approved'";

                $income_result = mysqli_query($conn, $income_query);

                if ($income_result) {
                    $income_data = mysqli_fetch_assoc($income_result);
                    echo number_format($income_data['daily_income'] ?? 0, 2);
                } else {
                    echo "Query Error";
                }
                ?>
        </span>
        <div class="infobox-content">New Today</div>
    </div>
</div>
                                    </div>
                                    
                                    <!-- Total Customers Card -->
                                    <div class="col-sm-4">
                                        <div class="infobox infobox-blue">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-users"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number">
                                                    <?php 
                                                    $total_customers = mysqli_query($conn, "SELECT COUNT(*) FROM clientslanddata");
                                                    echo mysqli_fetch_array($total_customers)[0];
                                                    ?>
                                                </span>
                                                <div class="infobox-content">Total Customers</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Pending Decision Card -->
                                    <div class="col-sm-4">
                                        <div class="infobox infobox-orange">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-paper-plane"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number">
                                                    <?php 
													$id1 = $_SESSION['SESS_ID'];
                                                    $pending_decision = mysqli_query($conn, "SELECT COUNT(*) FROM clientslanddata WHERE forpayment_status='notapproved' AND userid='$id1'");
                                                    echo mysqli_fetch_array($pending_decision)[0];
                                                    ?>
                                                </span>
                                                <div class="infobox-content">Pending to send</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Dashboard -->

            <div class="page-content">
                <?php include('setting/settingpage.php'); ?>
                <!-- Your existing page content -->
            
                <div class="col-xs-12">
                    <div>
                        <?php  //include('slider.php');?>
                        <?php //include('thumbnail.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../footerboot.php'); ?>

    <!-- Your existing scripts -->
    <!-- ... -->
</body>
</html>
							
							
							
							
							
  								<div class="col-xs-12">
																	<div>
									 <?php  //include('slider.php');?>
									 	 <?php //include('thumbnail.php'); ?>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
			
 
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
		<script src="../assets/js/jquery.colorbox.min.js"></script>

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
        <meta charset="utf-8" />
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
	var $overflow = '';
	var colorbox_params = {
		rel: 'colorbox',
		reposition:true,
		scalePhotos:true,
		scrolling:false,
		previous:'<i class="ace-icon fa fa-arrow-left"></i>',
		next:'<i class="ace-icon fa fa-arrow-right"></i>',
		close:'&times;',
		current:'{current} of {total}',
		maxWidth:'100%',
		maxHeight:'100%',
		onOpen:function(){
			$overflow = document.body.style.overflow;
			document.body.style.overflow = 'hidden';
		},
		onClosed:function(){
			document.body.style.overflow = $overflow;
		},
		onComplete:function(){
			$.colorbox.resize();
		}
	};

	$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
	$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");//let's add a custom loading icon
	
	
	$(document).one('ajaxloadstart.page', function(e) {
		$('#colorbox, #cboxOverlay').remove();
   });
})
		</script>
		<script>
				function show2(){
				if (!document.all&&!document.getElementById)
				return
				thelement=document.getElementById? document.getElementById("tick2"): document.all.tick2
				var Digital=new Date()
				var hours=Digital.getHours()
				var minutes=Digital.getMinutes()
				var seconds=Digital.getSeconds()
				var dn="PM"
				if (hours<12)
				dn="AM"
				if (hours>12)
				hours=hours-12
				if (hours==0)
				hours=12
				if (minutes<=9)
				minutes="0"+minutes
				if (seconds<=9)
				seconds="0"+seconds
				var ctime=hours+":"+minutes+":"+seconds+" "+dn
				thelement.innerHTML=ctime
				setTimeout("show2()",1000)
				}
				window.onload=show2
				//-->
				</script>
				<?php //echo date("g:i a"); ?>&nbsp;|&nbsp;<?php echo date("l F d, Y"); ?></strong></span>
				</div>
				<?php include('../footerboot.php'); ?>
			</div>
	</body>
</html>

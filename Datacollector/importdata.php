<?php include('setting/header.php'); ?>
<?php include('../db/connection.php');
$username=$_SESSION['SESS_USER_NAME'];
            $userid=$_SESSION['SESS_ID'];	
            include("../logactivity.php");
//$letter_folders1="Letters";	 ?>
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
	</head>
	
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
			

					<div class="page-content">
	                   <?php include('setting/settingpage.php'); ?>
					  <!-- /.ace-settings-container -->

							<div class="page-header">
							<h1><small>
								
									<a href="index">Home</a>
								<i class="ace-icon fa fa-angle-double-right"></i>
								<a href="index">Dashboard </a>
								<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div>
  								<div class="col-xs-12">
																	<div>
								
										 			<h5>Import File in CSV format</h5>
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group"> 
					<input type="file" id="file" accept=".csv, .xlsx" required name="file">
				</div>
				<button type="submit" name="import" class="btn btn-primary btn-sm">Import</button>
			</form></br>
					<div id="dt">
						<input type="text" name="barcode" style="width: 360px;" placeholder="ድለ...." id="filter" tabindex="1" /><br><br>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ini_set('memory_limit', '1024M'); // Optional: allow large Excel files

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// --- Configuration ---
$expectedHeaders = [
    'id', 'userid', 'fullname', 'phone', 'kebele', 'block', 'east', 'west', 'north', 'south',
    'filenumber', 'levelofplace', 'occupiedyear', 'mainservice', 'kindofvolumeofland',
    'area', 'beginofconstruction', 'endofconstruction', 'beginpayment', 'endpayment',
    'amountofpay', 'forpayment_status', 'siteplan', 'buildingplan', 'registeredby', 'registeredtime'
];

// --- Helper: Normalize year fields ---
function safeYear($val) {
    $val = trim((string)$val);
    return (is_numeric($val) && (int)$val > 0) ? (int)$val :0; //default year=0
}

// --- Helper: Insert one cleaned row ---
function insertDataRow($conn, $sql, $row, $rowNumber) {
    global $expectedHeaders;

    $row = array_map(fn($v) => trim((string)($v ?? '')), $row);

    // Pad or trim to 26 columns
    if (count($row) > 26) {
        $row = array_slice($row, 0, 26);
    }
    while (count($row) < 26) {
        $row[] = '';
    }

    if (count($row) !== 26) {
        echo "Skipping row $rowNumber: column count = " . count($row) . "<br>";
        return false;
    }

    // --- Detect and skip repeated header rows ---
    $normalizedRow = array_map(fn($v) => strtolower(trim($v)), $row);
    $headersToCheck = array_slice($normalizedRow, 0, count($expectedHeaders));

    if ($headersToCheck === $expectedHeaders) {
        echo "Skipping row $rowNumber: detected header row.<br>";
        return false;
    }

    // Check for empty filenumber
    $filenumber = $row[10];
    if (empty($filenumber)) {
        echo "Skipping row $rowNumber: filenumber is empty.<br>";
        return false;
    }

    // Check for duplicates
    $checkStmt = $conn->prepare("SELECT id FROM clientslanddata WHERE filenumber = ?");
    $checkStmt->bind_param("s", $filenumber);
    $checkStmt->execute();
    $checkStmt->store_result();
   /*  if ($checkStmt->num_rows > 0) {
        echo "Skipping row $rowNumber: filenumber '{$filenumber}' already exists.<br>";
        $checkStmt->close();
        return false;
    } */
    $checkStmt->close();
    // Type conversion
    $row[0] = (int)$row[0];        // id
    $row[1] = (int)$row[1];        // userid
    $row[4] = (int)$row[4];        // kebele
    $row[14] = trim($row[14]);     // kindofvolumeofland - string
    $row[15] = (float)$row[15];    // area
    $row[16] = safeYear($row[16]); // beginofconstruction
    $row[17] = safeYear($row[17]); // endofconstruction
    $row[18] = safeYear($row[18]); // beginpayment
    $row[19] = safeYear($row[19]); // endpayment
    $row[20] = (float)$row[20];    // amountofpay
    $row[21] = trim($row[21]);     // forpayment_status - string
    $row[22] = (int)$row[22];      // siteplan
    $row[23] = (int)$row[23];      // buildingplan

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: " . $conn->error . "<br>";
        return false;
    }

    $stmt->bind_param(str_repeat("s", 26), ...$row);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error inserting row $rowNumber: " . $stmt->error . "<br>";
        $stmt->close();
        return false;
    }
}

// --- Main Import Logic ---
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileName = $_FILES['file']['name'];
    $fileTmp  = $_FILES['file']['tmp_name'];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $sql = "INSERT INTO clientslanddata (
        id, userid, fullname, phone, kebele, block, east, west, north, south,
        filenumber, levelofplace, occupiedyear, mainservice, kindofvolumeofland,
        area, beginofconstruction, endofconstruction, beginpayment, endpayment,
        amountofpay, forpayment_status, siteplan, buildingplan, registeredby, registeredtime
    ) VALUES (" . str_repeat("?,", 25) . "?)";

    $imported = 0;
    $rowNumber = 1;

    if ($extension === 'xlsx') {
        $reader = IOFactory::createReaderForFile($fileTmp);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($fileTmp);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        foreach ($rows as $row) {
            $cleanRow = array_values($row);
            if (insertDataRow($conn, $sql, $cleanRow, $rowNumber)) {
                $imported++;
            }
            $rowNumber++;
        }
        echo "XLSX file processed. Rows inserted: $imported<br>";

    } elseif ($extension === 'csv') {
        if (($handle = fopen($fileTmp, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (insertDataRow($conn, $sql, $row, $rowNumber)) {
                    $imported++;
                }
                $rowNumber++;
            }
            fclose($handle);
            echo "CSV file processed. Rows inserted: $imported<br>";
        } else {
            echo "Unable to open CSV file.";
        }

    } else {
        echo "Only XLSX and CSV files are supported.";
        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        insertLog($conn, $userid ?? 0, $username ?? 'Unknown', "Import Attempt", "Unsupported file type: $extension", "Fail", $ip, $agent);
        exit;
    }

    // Log success
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    insertLog($conn, $userid ?? 0, $username ?? 'Unknown', "Client Importing", "Client data uploaded", "Success", $ip, $agent);

} else {
    echo "No file uploaded or upload error.";
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    insertLog($conn, $userid ?? 0, $username ?? 'Unknown', "Import Attempt", "No file uploaded or file error", "Fail", $ip, $agent);
}
?>





					</div>
								<div class="col-xs-12">
										 
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
														<th rowspan='3' class="green" class="center"> ታ.ቁ	</th>
															
													
														<th rowspan='3' class="green">ሽም ምስ ኣባሓጎ</th>
													<th rowspan='3' class="green"> ስልኪ ቁፅሪ</th>
														<th rowspan='3' class="green"> ቀበሌ</th>
														<th  rowspan='3' class="green">ብሎክ</th>
														<th colspan='4' class="green"> ኣዋሳኒ	</th>	
														<th  rowspan='3' class="green"> ፋ/ቁፅሪ	</th>									
														<th   rowspan='3' class="green"> ደረጃ ቦታ	</th>	
                                                        <th   rowspan='3' class="green"> ዝተታሓዘሉ እዋን 	</th>	
                                                         <th  rowspan='3' class="green"> ዓ/ግልጋሎት	</th>														
														<th  rowspan='3' class="green">   ናይቲ መሬት ዓይነት ትሕዝቶ	</th>	
														<th   rowspan='3' class="green"> ስፍሓት ብካ/ሜ	</th>	
														<th colspan='4' class="green"> ምእሳር ውዕል	</th>	
														<th  rowspan='3' class="green"> ክፍሊት ዝምልከት	</th>
	                                                     <th  colspan='2'class="green"> ፕላን	</th>	
														 <th  rowspan='3' class="green"> ባዓል ሞያ   </th>
														 <th  rowspan='3' class="green"> ዝተመዝገበሉ ግዘ  </th>
														<th colspan='2' rowspan='3' class="green">ተግብር</th>
														</tr>
														<tr>
														<th rowspan='2'class="green"> ምብ</th>
														<th rowspan='2'class="green"> ምዕ</th>
														<th rowspan='2'class="green"> ሰሜ</th>
														<th rowspan='2'class="green"> ደቡ</th>
														
														<th  colspan='2' class="green"> ህንፀት  </th>
														<th   colspan='2' class="green"> ክፍሊት  </th>
														<th rowspan='2' class="green"> ሳይት ፕላን  </th>
														<th  rowspan='2' class="green"> ህንፃ  ፕላን   </th>
														
														</tr>
														<tr>  
														<th class="green"> መጀመሪ </th>
														<th   class="green"> መወድኢ   </th>
														<th  class="green"> መጀመሪ </th>
														<th   class="green"> መወድኢ   </th>
														</tr>
												</thead>
												<tbody>
															<?php
    $i = 0;
    

   $q = "SELECT * FROM `clientslanddata` ORDER BY id DESC";

if ($stmt = mysqli_prepare($conn, $q)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

        // Loop through the results
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $fullname = $row['fullname'];
			 $phone = $row['phone'];
            $kebele = $row['kebele'];
            $block = $row['block'];
            $east = $row['east'];
            $west = $row['west'];
            $north = $row['north'];
            $south = $row['south'];
            $filenumber = $row['filenumber'];
            $levelofplace = $row['levelofplace'];
            $occupiedyear = $row['occupiedyear'];
            $mainservice = $row['mainservice'];
            $kindofvolumeofland = $row['kindofvolumeofland'];
            $area = $row['area'];
            $beginofconstruction = $row['beginofconstruction'];
            $endofconstruction = $row['endofconstruction'];
            $beginpayment = $row['beginpayment'];
            $endpayment = $row['endpayment'];
            $amountofpay = $row['amountofpay'];
            $siteplan = $row['siteplan'];
            $buildingplan = $row['buildingplan'];
            $i++;
     
?>

													<tr>
														<td class="center">
															<label class="pos-rel">
																<?php echo $i; ?>
																<span class="lbl"></span>
															</label>
														</td>

														<td><?php echo $row['fullname']; ?>	</td>
														<td><?php echo $row['phone']; ?>	</td>
														<td><?php echo $row['kebele']; ?></td>
														<td> <?php echo $row['block']; ?> </td>
														<td><?php echo $row['east']; ?>	</td>
														<td><?php echo $row['west']; ?></td>
														<td><?php echo $row['north']; ?></td>
														<td><?php echo $row['south']; ?></td>
														<td><?php echo $row['filenumber']; ?></td>
														<td><?php echo $row['levelofplace']; ?></td>
														<td><?php echo $row['occupiedyear']; ?></td>
														<td><?php echo $row['mainservice']; ?></td>
														<td><?php echo $row['kindofvolumeofland']; ?></td>
														<td> <?php echo $row['area']; ?> </td>
														<td><?php echo $row['beginofconstruction']; ?></td>
														<td><?php echo $row['endofconstruction']; ?></td>
														<td><?php echo $row['beginpayment']; ?></td>
														<td><?php echo $row['endpayment']; ?></td>
														<td><?php echo $amountofpay; ?></td>
														<td><?php echo $siteplan; ?></td>
														   <td><?php echo $buildingplan; ?></td>
														   <td><?php echo $row['registeredby'];?></td>
														   <td><?php echo $row['registeredtime']; ?></td>

														

														<td>
															<div class="hidden-sm hidden-xs action-buttons">
															
																<a class="blue" href="edit_clientlanddetail<?php  echo '?id='.$id; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																	<i class="ace-icon fa fa-pencil-square-o bigger-130"></i>
																</a>
															</div>
														</td>
															<td>
															<div class="hidden-sm hidden-xs action-buttons">

																<!--<a class="red" href="delete_clientlanddetail<?php //echo '?id='.$id; ?>" class="tooltip-success" data-rel="tooltip" title="Delete">
																	<i class="ace-icon fa fa-trash bigger-130"></i>
																</a> -->
															</div>
														</td>
													</tr>
 
	<?php }} ?>
												</tbody>
											</table>
										</div>
										</div>
									</div>
					 </div>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
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
					   null, null, null,    null, null,null, null, null, null, null, null, null, null, null, null, null,
					   null, null, null, null, null, null, null,   
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
			</div>
	</body>
</html>

<?php require_once('setting/header.php'); ?>
<?php require_once('../db/connection.php'); ?>
<head>
    <title>View Land & Homeowner Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background:#f0f4f8;
            color:#333;
        }
        fieldset {
            border: 2px solid #2c6ed5;
            border-radius: 8px;
            margin: 20px auto;
            padding: 20px;
            width: 95%;
            background:#fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        legend {
            font-weight: bold;
            font-size: 18px;
            color:#2c6ed5;
            padding: 0 10px;
        }
        form {
            margin-bottom: 20px;
            text-align:center;
        }
        input[type="text"] {
            padding: 8px 12px;
            margin: 6px;
            border:1px solid #2c6ed5;
            border-radius:4px;
            width:220px;
        }
        button {
            padding: 8px 16px;
            margin: 6px;
            border:none;
            border-radius:4px;
            background:#2c6ed5;
            color:#fff;
            font-weight:bold;
            cursor:pointer;
            transition:background 0.3s;
        }
        button:hover {
            background:#1d4ea3;
        }
        .record-box {
            width: 100%;
            overflow-x:auto;
            margin: auto;
            border: 1px solid #2c6ed5;
            padding: 20px;
            border-radius: 8px;
            background:#fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 0.5px  #2c6ed5;
            text-align: left;
        }
        th {
            background-color: green;
            color:#fff;
        }
        /* Alternating attractive colors */
        tr:nth-child(even) {
            background:pink;
        }
        tr:nth-child(odd) {
            background:#ffffff;
        }
        tr:hover {
            background:#e6f0ff;
        }
        .message {
            color: red;
            text-align: center;
            font-weight:bold;
            margin: 15px 0;
        }
        a.blue {
            color:#2c6ed5;
            text-decoration:none;
            font-weight:bold;
        }
        a.blue:hover {
            text-decoration:underline;
        }
        /* Pagination styles */
        .pagination {
            margin-top:15px;
            text-align:center;
        }
        .pagination a {
            display:inline-block;
            padding:6px 12px;
            margin:2px;
            border:1px solid #2c6ed5;
            border-radius:4px;
            color:#2c6ed5;
            text-decoration:none;
            font-weight:bold;
        }
        .pagination a.active {
            background:#2c6ed5;
            color:#fff;
        }
        .pagination a:hover {
            background:#1d4ea3;
            color:#fff;
        }
    </style>
</head>
<body class="no-skin">
<?php 
require_once('setting/headernav1.php'); 
$username = $_SESSION['SESS_USER_NAME'];
$useridc  = $_SESSION['SESS_ID'];	
include("../logactivity.php");
?>

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar responsive ace-save-state">
        <script type="text/javascript">
            try{ace.settings.loadState('sidebar')}catch(e){}
        </script>
        <?php require_once('setting/menu.php'); ?>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
               data-icon1="ace-icon fa fa-angle-double-left"
               data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="col-xs-12">
                <div class="col-xs-12">

<?php
require_once('../db/connection.php'); 

// ==============================
// HOMEOWNERS SECTION
// ==============================
$homeowner_id   = isset($_GET['homeowner_id']) ? trim($_GET['homeowner_id']) : '';
$homeowner_name = isset($_GET['homeowner_name']) ? trim($_GET['homeowner_name']) : '';
$homeowner_records = [];
$homeowner_message = '';

if ($homeowner_id !== '' || $homeowner_name !== '') {
    $where = "WHERE 1=1";
    $params = [];
    $types  = "";

    if ($homeowner_id !== '') {
        $where .= " AND REPLACE(homeowner_id,' ','')=?";
        $params[] = str_replace(' ','',$homeowner_id);
        $types .= "s";
    }
    if ($homeowner_name !== '') {
        $where .= " AND REPLACE(name,' ','')=?";
        $params[] = str_replace(' ','',$homeowner_name);
        $types .= "s";
    }

    $query = "SELECT id,homeowner_id,name,phone,rooms,room_cost 
              FROM homeowners $where ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            while ($r = $res->fetch_assoc()) {
                $homeowner_records[] = $r;
            }
        } else {
            $homeowner_message = "ዝካረ ገዛ የብሎምን::";
        }
        $stmt->close();
    }
}
?>

<fieldset>
    <legend>ዝተመዝገቡ መካረይቲ ገዛ</legend>
    <form method="GET" action="">
        <input type="text" name="homeowner_id" placeholder="መለለይ ቁፅሪ" value="<?= htmlspecialchars($homeowner_id) ?>" />
        <input type="text" name="homeowner_name" placeholder="ሙሉእ ሽም" value="<?= htmlspecialchars($homeowner_name) ?>" />
        <button type="submit">ድለ</button>
    </form>

    <?php if ($homeowner_message): ?>
        <div class="message"><?= htmlspecialchars($homeowner_message) ?></div>
    <?php elseif (!empty($homeowner_records)): ?>
    <div class="record-box">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>መለለይ ቁፅሪ</th>
                    <th>ሙሉእ ሽም</th>
                    <th>ስልኪ ቁፅሪ</th>
                    <th>በዝሒ ዝካረ ገዛ</th>
                    <th>ናይ ሓደ ገዛ ክፍሊት</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($homeowner_records as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($r['homeowner_id']) ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['phone']) ?></td>
                    <td><?= htmlspecialchars($r['rooms']) ?></td>
                    <td><?= htmlspecialchars($r['room_cost']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</fieldset>

<?php
// ==============================
// LAND RECORDS SECTION WITH PAGINATION
// ==============================
$filenumber = isset($_GET['filenumber']) ? trim($_GET['filenumber']) : '';
$records = [];
$message = '';
$forpayment_status = 'approved';

// Pagination setup
$limit = 5; // rows per page
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_rows = 0;
$total_pages = 0;

if ($filenumber !== '') {
    // Count total
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM clientslanddata WHERE filenumber=? AND forpayment_status=?");
    $countStmt->bind_param("ss", $filenumber, $forpayment_status);
    $countStmt->execute();
    $countStmt->bind_result($total_rows);
    $countStmt->fetch();
    $countStmt->close();

    $total_pages = ceil($total_rows / $limit);

    // Fetch with limit
    $stmt = $conn->prepare("SELECT `id`, `userid`, `fullname`, `phone`, `kebele`, `block`, `east`, `west`, `north`, `south`, `filenumber`, `levelofplace`, `occupiedyear`, `mainservice`, `kindofvolumeofland`, `area`, `beginofconstruction`, `endofconstruction`, `beginpayment`, `endpayment`, `amountofpay`, `forpayment_status`, `siteplan`, `buildingplan`, `registeredby`, `registeredtime` 
                             FROM `clientslanddata`
                             WHERE filenumber = ? AND forpayment_status = ?
                             LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $filenumber, $forpayment_status, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    } else {
        $message = "No record found for File Number: $filenumber";
    }
    $stmt->close();
}
?>

<fieldset>
    <legend>ተጠቀምቲ ብፍ/ቁፅሪ ምድላይ</legend>
    <form method="GET" action="">
        <label for="filenumber">ፋ/ቁፅሪ:</label>
        <input type="text" name="filenumber" id="filenumber" placeholder='ፋ/ቁፅሪ' value="<?= htmlspecialchars($filenumber) ?>" required />
        <button type="submit">ድለ</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($records)): ?>
    <div class="record-box">
        <table>
            <thead>
                <tr>
                    <th>ታ.ቁ</th>
					<th>መለለይ ቁፅሪ </th>
                    <th>ሽም ምስ ኣባሓጎ</th>
                    <th>ስልኪ ቁፅሪ</th>
                    <th>ቀበሌ</th>
                    <th>ብሎክ</th>
                    <th>ፋ/ቁፅሪ</th>
                    <th>ደረጃ ቦታ</th>
                    <th>ዝተታሓዘሉ እዋን</th>
                    <th>ዓ/ግልጋሎት</th>
                    <th>ናይቲ መሬት ዓይነት ትሕዝቶ</th>
                    <th>ስፍሓት ብካ/ሜ</th>
                    <th>ህንፀት መጀመሪ</th>
                    <th>ህንፀት መወድኢ</th>
                    <th>ክፍሊት መጀመሪ</th>
                    <th>ክፍሊት መወድኢ</th>
                    <th>ዝከፍሎ መጠን ገንዘብ</th>
                    <th>ተግብር</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $i => $row): ?>
                <tr>
                    <td><?= $offset + $i + 1 ?></td>
					 <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['kebele']) ?></td>
                    <td><?= htmlspecialchars($row['block']) ?></td>
                    <td><?= htmlspecialchars($row['filenumber']) ?></td>
                    <td><?= htmlspecialchars($row['levelofplace']) ?></td>
                    <td><?= htmlspecialchars($row['occupiedyear']) ?></td>
                    <td><?= htmlspecialchars($row['mainservice']) ?></td>
                    <td><?= htmlspecialchars($row['kindofvolumeofland']) ?></td>
                    <td><?= htmlspecialchars($row['area']) ?></td>
                    <td><?= htmlspecialchars($row['beginofconstruction']) ?></td>
                    <td><?= htmlspecialchars($row['endofconstruction']) ?></td>
                    <td><?= htmlspecialchars($row['beginpayment']) ?></td>
                    <td><?= htmlspecialchars($row['endpayment']) ?></td>
                    <td><?= htmlspecialchars($row['amountofpay']) ?></td>
                    <td>
                        <a class="blue" href="rentpaymentdeciding?id=<?= $row['id'] ?>" title="ግብሪ ወስን">
                            <i class="ace-icon fa fa-eye bigger-130"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination links -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($p=1; $p <= $total_pages; $p++): ?>
                <a href="?filenumber=<?= urlencode($filenumber) ?>&page=<?= $p ?>" class="<?= $p==$page?'active':'' ?>"><?= $p ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</fieldset>

<?php 
//  Record log activity
$ip    = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
insertLog($conn, $useridc, $username, "Viewed", "Viewed land and homeowner records.", "success", $ip, $agent);  
?>
                </div>
            </div>
        </div>
    </div>
</div>

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
					   null, null, null,    null, null,null, null, null, null, null, null, null, null, null,
					   null, null, null,  null,
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


<?php 
include('setting/header.php'); 
include('../db/connection.php');

if (session_status() === PHP_SESSION_NONE) session_start();
$username = htmlspecialchars($_SESSION['SESS_USER_NAME'], ENT_QUOTES, 'UTF-8'); // sanitize session data
$useridc = (int)$_SESSION['SESS_ID']; // ensure numeric
include("../logactivity.php");
$id1 = (int)$_SESSION['SESS_ID'];

// Fetching and sanitizing client_id from URL (GET request)
$client_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$client_id) {
    echo "<div class='alert alert-danger'>Invalid Client ID.</div>";
    exit;
}

// ======================
// Fetch existing homeowner record
// ======================
$query = "SELECT id, name, phone, rooms, room_cost FROM homeowners WHERE id = ? ORDER BY id DESC LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $client_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $homeowner_id, $owner_name_raw, $owner_phone_raw, $rooms_existing, $room_cost_existing);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$homeowner_id) {
    echo "<div class='alert alert-danger'>No homeowner record found for this client.</div>";
    exit;
}

// Sanitize output for display
$owner_name = htmlspecialchars($owner_name_raw, ENT_QUOTES, 'UTF-8');
$owner_phone = htmlspecialchars($owner_phone_raw, ENT_QUOTES, 'UTF-8');

// ======================
// Handle form submission
// ======================
if (isset($_POST['register'])) {
    // Sanitize inputs
	$registeredtime = htmlspecialchars(
    trim(filter_input(INPUT_POST, 'ethiopianDate1', FILTER_SANITIZE_STRING)), ENT_QUOTES, 'UTF-8');
    $rooms = filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT);
    $room_cost = filter_input(INPUT_POST, 'room_cost', FILTER_VALIDATE_FLOAT);

    if ($rooms === false || $room_cost === false) {
        echo '<script>
                alert("Invalid input values");
                window.location.href = "viewrent";
              </script>';
        exit;
    }

    // Prepared statement for update
    $sql_update = "UPDATE homeowners 
                   SET rooms = ?, room_cost = ?, registered_at = ? 
                   WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "disi",  $rooms, $room_cost,$registeredtime, $homeowner_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Sanitize IP and User Agent for logging
    $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ?: '0.0.0.0';
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8') : 'unknown';

    // Insert log activity
    insertLog($conn, $useridc, $username, "Edited", "Homeowner Detail is updated successfully.", "success", $ip, $agent);

    echo '<script>
        alert("ብትክክል ተስተካኪሉ ኣሎ");
        window.location.href = "viewrent";
    </script>';
}
?>
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
        try { ace.settings.loadState('main-container'); } catch (e) {}  
    </script>

    <div id="sidebar" class="sidebar responsive ace-save-state">
        <?php include('setting/menu.php'); ?>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" 
               class="ace-icon fa fa-angle-double-left ace-save-state" 
               data-icon1="ace-icon fa fa-angle-double-left" 
               data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <?php include('setting/settingpage.php'); ?>

            <div class="col-xs-12">
                <h4 class="lighter"> 
                    <div class="tab-pane fade in active" id="homeowner_renter">

                        <form class="form-horizontal" method="post">
                            <fieldset style="border: 2px solid #4CAF50; padding: 20px; background-color: #E8F5E9; border-radius: 10px;">
                                <legend style="font-size: 18px; font-weight: bold; color: #388E3C; padding: 0 10px; border-bottom: 2px solid #388E3C;">
                                    <i class="fa fa-home"></i> ናይ መካረይቲ ገዛ ሓበሬታ ምምሕያሽ
                                </legend>
								 <div class="form-group">
								 <span  id="ethiopianDate" type="hidden" align="right"></span>
									<label class="col-sm-3 control-label">ዕለት:</label>
									<div class="col-sm-3">
									   <input  id="ethiopianDate1" type="text" readonly name="ethiopianDate1"  required />
									</div>
								</div> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ሽም (ባዓል ገዛ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="owner_name" value="<?= $owner_name ?>" required class="form-control" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ስልኪ (ባዓል ገዛ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="owner_phone" value="<?= $owner_phone ?>" required class="form-control" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">በዝሒ ዝካረ ገዛ:</label>
                                    <div class="col-sm-3">
                                        <input type="number" name="rooms" value="<?= htmlspecialchars($rooms_existing) ?>" required class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ናይ ሓደ ክራይ ክፍሊት(ብማእከላይ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="room_cost" value="<?= htmlspecialchars($room_cost_existing) ?>" required class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="register" class="btn btn-success" style="padding: 10px 20px; font-size: 16px; border-radius: 5px;">
                                        <i class="fa fa-edit"></i> ኣስተካክል
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </h4>
            </div>
        </div>
    </div>
</div>

<?php include('../footerboot.php'); ?>







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


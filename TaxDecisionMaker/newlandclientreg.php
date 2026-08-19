<?php include('setting/header.php'); ?>
<?php include('../db/connection.php'); ?>

 <?php //$userid=$_SESSION['SESS_ID']; 
 ?>
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
	                   <?php include('setting/settingpage.php'); 
					   
					   
					   ?>
					  <!-- /.ace-settings-container -->

  					 <div class="col-xs-12">
								
									<div class="col-xs-12">
									<h4 class="lighter">
                                
								
								
								 

<?php 

$i = 0;
$id = $_GET['id'];
$id1 = $_SESSION['SESS_ID'];

// Prepare the SQL SELECT query with placeholders
$query = "SELECT * FROM `clientslanddata` WHERE  `forpayment_status` = 'approved' AND `id` = ?  ORDER BY `id` DESC";

// Prepare the statement
if ($stmt = mysqli_prepare($conn, $query)) {
    // Bind the parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "i",  $id);  // "ii" means two integer parameters

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result of the query
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the data and assign it to variables
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $fullname = $row['fullname'];
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

								
								
								
								
                             <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
										 <fieldset>
										 <legend>ዝርዝር ተጠቀምቲ መሬት </legend>
										 
									      <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ሽም ምስ ኣባሓጎ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  value='<?php echo $fullname; ?>' name="fullname"  readonly required id="fullname" placeholder=" ሽም ምስ ኣባሓጎ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ቀበሌ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  value='<?php echo $kebele; ?>' name="kebele" readonly required id="kebele" placeholder="ቀበሌ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ብሎክ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="block"  value='<?php echo $block; ?>'  readonly required id="block" placeholder="ብሎክ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ኣዋሳኒ:  </label>
									    </div> 
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምብራቅ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="east"  value='<?php echo $east; ?>' readonly required id="east" placeholder="ምብ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምዕራብ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="west"  value='<?php echo $west; ?>' readonly required id="west" placeholder="ምዕ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ሰሜን:  </label>
										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="north"  required id="north"  value='<?php echo $north; ?>' readonly placeholder="ሰሜ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ደቡብ:  </label>
										<div class="col-sm-10">
											 <input type="text" id="form-field-1"  name="south"  value='<?php echo $south; ?>' readonly required id="south" placeholder="ደቡ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ፋ/ቁፅሪ:  </label>
                                           <div class="col-sm-10">
											<input type="text" id="form-field-1"  name="filenumber"   value='<?php echo $filenumber; ?>' readonly required id="filenumber" placeholder="ፋ/ቁፅሪ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ደረጃ ቦታ:  </label>
                                           <div class="col-sm-10">
											<input type="text" id="form-field-1"  name="levelofplace"   value='<?php echo $levelofplace; ?>' readonly required id="levelofplace" placeholder="ደረጃ ቦታ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ዝተታሓዘሉ እዋን:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="occupiedyear"  value='<?php echo $occupiedyear; ?>' readonly required id="occupiedyear" placeholder="ዝተታሓዘሉ እዋን" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
								 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ዓ/ግልጋሎት:   </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="mainservice"  value='<?php echo $mainservice; ?>' readonly required id="mainservice" placeholder="ዓ/ግልጋሎት" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
						
	                            <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ናይቲ መሬት ዓይነት ትሕዝቶ:   </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="kindofvolumeofland"  value='<?php echo $kindofvolumeofland; ?>' readonly required id="kindofvolumeofland" placeholder="ናይቲ መሬት ዓይነት ትሕዝቶ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>

									
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ስፍሓት ብካ/ሜ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="area"  value='<?php echo $area; ?>' required id="area" placeholder="ስፍሓት ብካ/ሜ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምእሳር ውዕል:  </label><br/>
										</div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ህንፀት መጀመሪ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="beginofconstruction"  value='<?php echo $beginofconstruction; ?>' readonly required id="beginofconstruction" placeholder="ህንፀት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ህንፀት መወድኢ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="endofconstruction"  value='<?php echo $endofconstruction; ?>' readonly required id="endofconstruction" placeholder="ህንፀት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ክፍሊት መጀመሪ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="beginpayment"  value='<?php echo $beginpayment; ?>' readonly required id="beginpayment" placeholder="ክፍሊት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										 <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ክፍሊት መወድኢ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="endpayment" value='<?php echo $endpayment; ?>' readonly required id="endpayment" placeholder="ክፍሊት መወድኢ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ዝከፍሎ  መጠን ገንዘብ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="amountofpay"  value='<?php echo $amountofpay; ?>' readonly required id="amountofpay" placeholder="ዝከፍሎ  መጠን ገንዘብ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1" >   </label>

										  <div class="col-sm-10">
										   <div class="form-group">
															<div class="col-xs-5">
															<center>
											 <p>
											
										  <button type="save"  name="save" class="btn btn-white btn-info btn-bold">
												<i class="ace-icon fa fa-plus bigger-120 orange"></i>
												ኣስተካክል
											</button> &nbsp;&nbsp;&nbsp;&nbsp;

											<button type="reset" class="btn btn-white btn-default btn-round">
												<i class="ace-icon fa fa-times red2"></i>
												ኣፅሪ
											</button>
										</p>  </center>
														</div>
														</div>
													 	 
														</div>
														
											  	
									          </div>
											   </fieldset>
								</form>	<?php }} ?>
										  
	<?php
if (isset($_POST['save'])) {
    $id=$_GET['id'];

    // Retrieve form inputs
    $fullname = $_POST['fullname'];
    $kebele = $_POST['kebele'];
    $block = $_POST['block'];
    $east = $_POST['east'];
    $west = $_POST['west'];
    $north = $_POST['north'];
    $south = $_POST['south'];
    $filenumber = $_POST['filenumber'];
    $levelofplace = $_POST['levelofplace'];
    $occupiedyear = $_POST['occupiedyear'];
    $mainservice = $_POST['mainservice'];
    $kindofvolumeofland = $_POST['kindofvolumeofland'];
    $area = $_POST['area'];
    $beginofconstruction = $_POST['beginofconstruction'];
    $endofconstruction = $_POST['endofconstruction'];
    $beginpayment = $_POST['beginpayment'];
    $endpayment = $_POST['endpayment'];
    $amountofpay = $_POST['amountofpay'];
    $siteplan = $_POST['siteplan'] ?? null;  // Ensure optional fields don't cause errors
    $buildingplan = $_POST['buildingplan'] ?? null;
    //$id = $_POST['id']; // Ensure this is provided in the form

    // SQL Query with placeholders
    $sql = "UPDATE `clientslanddata` 
            SET `fullname` = ?, `kebele` = ?, `block` = ?, `east` = ?, `west` = ?, `north` = ?, `south` = ?, 
                `filenumber` = ?, `levelofplace` = ?, `occupiedyear` = ?, `mainservice` = ?, `kindofvolumeofland` = ?, `area` = ?, 
                `beginofconstruction` = ?, `endofconstruction` = ?, `beginpayment` = ?, `endpayment` = ?, `amountofpay` = ?, 
                `forpayment_status` = 'notapproved', `siteplan` = ?, `buildingplan` = ?, `registeredtime` = CURRENT_TIMESTAMP 
            WHERE `forpayment_status` = 'notapproved' AND `id` = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters (s = string, i = integer, d = double)
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssi", 
            $fullname, $kebele, $block, $east, $west, $north, $south, 
            $filenumber, $levelofplace, $occupiedyear, $mainservice, $kindofvolumeofland, $area, 
            $beginofconstruction, $endofconstruction, $beginpayment, $endpayment, $amountofpay, 
            $siteplan, $buildingplan, $id
        );

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo '<p class="success" style="color:#390"> Data Updated successfully</p>';
            echo '<meta http-equiv="refresh" content="2;url=newlandregistration" />';
        } else {
            echo '<p style="color:red">Error updating data: ' . mysqli_error($conn) . '</p>';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo '<p style="color:red">Error preparing statement: ' . mysqli_error($conn) . '</p>';
    }

    // Close database connection
    mysqli_close($conn);
}
?>

										
														
										 
								
												
									
									
								</h4>
										 
			
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
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					   null, null, null,  null, null, null, 
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


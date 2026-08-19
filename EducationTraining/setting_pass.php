<?php include('setting/header.php'); ?>
<?php include('../db/dbcon.php'); ?>
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
			 
			include('../db/log_in.php');
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
								
								<div class="intro-text">
           <div class="intro-lead-in">
				<center><p>
                  Please change your Password 
					<p></center>
				</div>
                
            </div>
							</h1>
						</div>
  								<div class="col-xs-12">
							 

	<div class="main-content">
													
     <div class="row">
					    
					<div class="col-sm-12">
						<div class="">
					
							<div class="position-relative">
								<!-- /.login-box -->

								<!-- /.forgot-box -->

								<div id="" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h5 class="header black lighter bigger">
												<i class="ace-icon fa fa-lock blue"></i>
												In order to change password, <br>1. provided your current password 
												<br>2. Enter new password 
												<br>3. confirm password of your new password.</h5>

											<?php $error=""; ?>
											<?php	
if(isset($_POST['save'])){
$user= $_SESSION['SESS_USER_NAME'];

$Current=$_POST['Current'];
$Current=md5($Current);
;
$New=$_POST['New'];

$Confirm=$_POST['Confirm'];

$result=mysqli_query($con, "SELECT * FROM `admin` WHERE user_name='$user'");
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result))
{ $pass= $row['password'];

}
}

if($pass==$Current)
{ if($New==$Confirm){
if(preg_match('#.*^(?=.{8,20})(?=.*[0-9])(?=.*\W).*$#',$New)) // pass is valid
    {

mysqli_query($conn, "UPDATE  `admin` SET `status` = '1' WHERE `admin`.`user_name`='$user';");
mysqli_query($conn, "UPDATE  `admin` SET `password` = MD5('$New') WHERE `admin`.`user_name` ='$user';");
$error="<p class='green'>Successfully Password changed  </p>";
  
}
else{
$error="<p class='red'>New Password is weak. use at least 8 characters which is a combunation of letters, symbols and numbers. </p>";
 
}
 
 
 } else{
$error="<p class='red'>
Mismatch confirmation  Password and new Password. </p>";
 
}

}else{
$error="<p class='red'>Your current password is wrong. </p>";
 
}

}
?>
					
											<form  method="post" >
												<fieldset>
												
														
													<div class="col-sm-3">
													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle green">
															</span>  Current Password:

														
													</label> </div>
													<div class="col-sm-9">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" required name="Current" id="Current" class="form-control-input-icon" placeholder="Current Password" />

														</span>
													</label></div>
													<div class="col-sm-3">
													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle green">
															 
														</span>  New Password:
													</label></div>
											
													<div class="col-sm-9">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" required name="New" id="New" class="form-control-input-icon" placeholder=" New Password" />
												
														</span>
													</label></div>
													
						
							
													<div class="col-sm-3">
													

													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle green">
															
														</span>   Confirm Password:
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="Confirm" id="Confirm" required class="form-control-input-icon" placeholder="Confirm Password" />

														</span>
													</label></div>
													
													
													<div class="col-sm-3">
													

													<label class="block clearfix">
														  
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
														<?php echo "<p class='red'>$error</p>";?>
														</span>
													</label></div>
													
													
													
													
													<div class="col-sm-3">
													

													<label class="block clearfix">
														 
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix">
													<button type="save" class="width-15 ace-icon fa fa-save bigger-120 blue" id="save" name="save" >&nbsp;&nbsp;Change Password</button>
														&nbsp;&nbsp;&nbsp;&nbsp;
														
	   <button type="reset" class="width-15 ace-icon fa fa-refresh bigger-140 red  "id="clear" name="clear" >&nbsp;&nbsp;Reset</button>
													</label></div>
													
													
													<div class="col-sm-6">

												

													<div class="space-24"></div>

													<div class="">
														

		
														
													</div></div>
												</fieldset>
											</form>
						
										</div>

										
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div>
			</div><!-- /.main-content -->
								
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
	</body>
</html>


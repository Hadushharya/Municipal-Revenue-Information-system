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
             
				<!-- /.sidebar-shortcuts -->
             <?php include('setting/menu.php'); ?>
			 <?php //$get_id = $_GET['id']; ?>
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
									 <a href="#">Position</a>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<a href="#">Add Setting Page</a>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
								
								
							</h1>
						</div>
            <div class="row">
					<div class="">
						<div class="">
							

	<?php	$coll_id="";
	  $error="";
    if(isset($_POST['submit'])){
$type1=$_POST['dep_name'];
$title_name=$_POST['title'];
  
 $user= $_SESSION['SESS_USER_NAME'];
  $id=$_SESSION['SESS_ID'];
   $result=mysqli_query($con, "SELECT *FROM `administrator` WHERE   user_name='$user'");
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result))
{ $fname= $row['fname'];
  $lname= $row['mname'];
}
}

 

mysqli_query($con, "INSERT INTO `construction`.`setting_page` (`set_id`, `type`, `mess`, `regist_by`, `regist_time`) 
VALUES (NULL, '$type1', '$title_name', '$fname $lname', CURRENT_TIMESTAMP);") ;
 
$error="Saved setting paging";

 
 }
?>
							
							<div class="position-relative">
								<!-- /.login-box -->

								<!-- /.forgot-box -->
 
									<div class="widget-body">
										<div class="widget-main">
											<h3 class="header black lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												Registration mission/Vission
											</h3>

											
							<form method="POST" enctype="multipart/form-data" name="upload">
									 				
												<fieldset>
												<div class="col-sm-6">
														<label class="block clearfix">
														<span class="form-control-input">
															 <select required  name="dep_name" id="dep_name">
													<option value="">Select Type setting...</option>
												 
													<option value="Mission">Mission</option>
													<option value="Vission">Vission</option>
													<option value="About">About</option>
												
													
												</select>
																<i class=" "></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="form-control-input">
															<textarea  type="text" class="form-control-input-icon" id="title"  required name="title" placeholder="The Messege" /></textarea>

														</span>
													</label> 
													 
													<span class="form-control-input">
														<button type="submit" class="width-15 ace-icon fa fa-save bigger-140 blue  "id="submit" name="submit" >&nbsp;&nbsp;Save...</button>
	
														&nbsp;&nbsp;&nbsp;&nbsp;
														<button type="reset" class="width-15 ace-icon fa fa-refresh bigger-140 red  "id="clear" name="clear" >&nbsp;&nbsp;Reset</button>
	                                     <?php  echo "<p class='red'>$error</p>"; ?>
													</span >
													</div>
													 
												</fieldset>
											 
											</form>
											
 
										

										
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
								 
									<div class="col-xs-12">
										
										<div class="clearfix">
  
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											Page Setting in the Systems of PRS 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<label class="pos-rel">
															
																<span class="lbl">No</span>
															</label>
														</th>
																												
														<th>Type settig </th>														
		
														
														<th>Messege</th>
														
														<th>Registed By</th>
														<th>Time's upload</th>
														
														<th>Action</th>
														
 
													</tr>
												</thead>

												<tbody>
												<?php
								$i=0;
								$result= "select * from setting_page " ;
								if($result=mysqli_query($con, $result))
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['set_id'];
								?>
													<tr>
													<td class="center">
															<label class="pos-rel">
															
																<span class="lbl"><?php $i+=1;echo $i;?></span>
															</label>
														</td>
													
													<td><a class="blue" href="#"><?php echo $row ['type']; ?></a></td>
														 <td class="hidden-480">
															<span class=""><a class="blue" href="#"><?php echo $row ['mess']; ?></a></span>
														</td>
														
														<td class="hidden-480">
															<span class=""><a class="blue" href="#"><?php echo $row ['regist_by']; ?></a></span>
														</td>
														<td class="hidden-480">
															<span class=""><a class="blue" href="#"><?php echo $row ['regist_time']; ?></a></span>
														</td>

													     <td>
															<div class="hidden-sm hidden-xs action-buttons">															
																<a class="green" href="e_set_page<?php echo '?id='.$id; ?>"  class="tooltip-info" data-rel="tooltip"   title="Edit">
																	<i class="ace-icon fa fa-pencil bigger-130" ></i>
																</a>

																<a class="red" href="d_set_page<?php echo '?id='.$id; ?>"  class="tooltip-info" data-rel="tooltip"   title="Delete">
																	<i class="ace-icon fa fa-trash-o bigger-130" ></i>
																</a>
															</div>
															<div class="hidden-md hidden-lg">
																 
														     </td>
													       </tr>
														<?php }// echo "<p class='red'>$error</p>"; ?>


	
                                                  </tbody>
											</table>
										</div>
									 
							 
								
								

					

					<br><br><br><br><br><br><br><br><br>
			</div>
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
					
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

		<!-- inline scripts related to this page -->
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
					  null, null,null,null,   
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
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: ''
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


<?php
 include('db/dbcon.php');   
include('head_index.php'); 
session_start();
session_destroy(); 
include('sidebar.php');
?>
 
	
<div class="page-content">
					 

						 

						<div class="row">
							<div class="col-xs-10">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div>
									<ul class="ace-thumbnails clearfix">
									   <?php
								
								 
                                $q="select * from file where type='images'";
                            
                            $result=mysqli_query($con,$q);
								 
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['file_id'];
								?>
										<li style="background-color:blue; color:white margin:1px; padding:1px; border:1px solid blue;">
											<a href="admin/page_setting/Image/<?php echo $row['path'];?>" title="<?php echo $row['title'];?>" data-rel="colorbox">
												<img width="150" height="150" alt="150x150" src="admin/page_setting/Image/<?php echo $row['path'];?>" />
											</a>
         </li>
										<?php } ?>
   
  
									</ul>
								</div>
								  </div>
							 
							<?php include('thumbnail.php'); ?>
										</div>
								 

								
								 

 

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="assets/js/jquery.colorbox.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

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
	</body>
</html>

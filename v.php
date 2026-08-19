
<?php
 include('db/dbcon.php');   
include('head_index.php'); 
session_start();
session_destroy(); 
include('sidebar.php');
?>
 
	
<div class="page-content">
					 

						 

						<div class="row">
							 
									   <div class="col-xs-12">
		   <?php $get_id = $_GET['id']; 

$result=mysqli_query($con, "SELECT * FROM `file` WHERE file_id='$get_id'  ");
									    if (mysqli_num_rows($result) > 0) 
										{
										while($row = mysqli_fetch_assoc($result))
										{ $categ= $row['type'];
											 
										}
										}

?>
     		   <?php
			   $discrip="";
			    $regist_time="";
				$discrip="";
				$title="";
			     $q="select * from file where file_id='$get_id'";
                            $p="admin/page_setting/Video/";
							$v="video";
							$path="";
							
                            $result=mysqli_query($con,$q);								 
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['file_id'];
								$file= $p.$path;
								$discrip=$row['descrip'];
								$title=$row['title'];
								$regist_time=$row ['regist_time'];
								$path=$row['path'];
									$org=explode('.',$path);
                                    $ex=array_pop($org);
                                    $ex=$ex;
									$ss=$v.$ex;
								}
								
								?>
								
								  <div class="col-sm-8"  >
										    <td>
											 <video width="730" height="440" controls autoplay loop title="<?php echo $discrip; ?>"   style="background-color:black; color:white margin:10px; padding:10px; border:10px solid Aqua;">
										     <source src="admin/page_setting/Video/<?php echo $path;?>" type="video/<?php echo $ex;?>">
											  </video> 
											  
											  
											  <?php echo "<br>Title:  ".$title;echo "<br>";echo "Discription:   ".$discrip; echo "<br> Time Uploaded: "; echo $regist_time;include('thumbnail.php'); ?>
											  
											 </td> 

											 </div >
					<div class="col-sm-4"  style="background-color:none ; color:white margin:10px; padding:10px; border:3px solid black;">
							
						<table id="dtable" width="100%"> 
							 <tr>
							<th colspan="3"><a href="#"><p class="red" size ="50"><u><b><i>       AKSUM UNIVERSITY OF VIDEOS     </u></b></i> </p></a><th></tr>
								 <tbody>
                       
							<?php 
							$categ="all";
							$categ="video";
                            if($categ=="all"){
                                $q="select * from file";
                            }
                            else{
                                $q="select * from file where type='$categ' ORDER BY `file`.`regist_time` DESC ";
                            }
                            $result=mysqli_query($con,$q);
								 
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['file_id'];
								$file= $p.$path;
								$path=$row['path'];
									$org=explode('.',$path);
                                    $ex=array_pop($org);
                                    $ex=$ex;
									$ss=$v.$ex;
								?>
								
								<tr>
							 <td style="background-color:white; color:white; margin:10px; padding:10px;">
							   <a class="" href="v<?php echo '?id='.$id; ?>"> <video width="150" height="80"  > <source src="admin/page_setting/Video/<?php echo $path;?>" type="video/<?php echo $ex;?>">
							   </video></a> &nbsp;<a class="" href="v<?php echo '?id='.$id; ?>" ><?php echo $row ['title']; ?>&nbsp;<a class="blue" href="v.php<?php echo '?id='.$id; ?>"> </td> 
							 </tr>
							  <?php } ?>
                            </tbody>
                           </table>
						   </div>
						   <div class="col-sm-6">
					 
					 </div >
				</div>
							 
								 

								
								 

 

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

 
		<?php include('footerboot_home.php'); ?>
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
		<!-- page specific plugin scripts -->
	 

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>


<script type="text/javascript">
			jQuery(function($) {
			 var $sidebar = $('.sidebar').eq(0);
			 if( !$sidebar.hasClass('h-sidebar') ) return;
			
			 $(document).on('settings.ace.top_menu' , function(ev, event_name, fixed) {
				if( event_name !== 'sidebar_fixed' ) return;
			
				var sidebar = $sidebar.get(0);
				var $window = $(window);
			
				//return if sidebar is not fixed or in mobile view mode
				var sidebar_vars = $sidebar.ace_sidebar('vars');
				if( !fixed || ( sidebar_vars['mobile_view'] || sidebar_vars['collapsible'] ) ) {
					$sidebar.removeClass('lower-highlight');
					//restore original, default marginTop
					sidebar.style.marginTop = '';
			
					$window.off('scroll.ace.top_menu')
					return;
				}
			
			
				 var done = false;
				 $window.on('scroll.ace.top_menu', function(e) {
			
					var scroll = $window.scrollTop();
					scroll = parseInt(scroll / 4);//move the menu up 1px for every 4px of document scrolling
					if (scroll > 17) scroll = 17;
			
			
					if (scroll > 16) {			
						if(!done) {
							$sidebar.addClass('lower-highlight');
							done = true;
						}
					}
					else {
						if(done) {
							$sidebar.removeClass('lower-highlight');
							done = false;
						}
					}
			
					sidebar.style['marginTop'] = (17-scroll)+'px';
				 }).triggerHandler('scroll.ace.top_menu');
			
			 }).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);
			
			 $(window).on('resize.ace.top_menu', function() {
				$(document).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);
			 });
			
			
			});
		</script>
	</body>
</html>


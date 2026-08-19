   
 
 
  
<?php
session_start();
$get_id = "0";
$user_name = "";
$email = "";

if (isset($_POST['login'])) {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];

    // Prepared statement to prevent SQL injection
    $query = "SELECT * FROM `new_researcher` WHERE email = ?";
    
    if ($stmt = mysqli_prepare($con, $query)) {
        // Bind the email parameter
        mysqli_stmt_bind_param($stmt, "s", $email);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $user_name = $row['user_name'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $mname = $row['mname'];
                $phone = $row['phone'];
                $email = $row['email'];
                $get_id = $row['res_id'];
            }

            // Redirect to the page with the res_id
            echo "<script>window.location = 'generate_information?res_id=$get_id';</script>";
        } else {
            // Handle case when no results are found, if needed
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error with query preparation if needed
    }
}
?>

            
 	<div class="main-content">
		<div class="row">

					<div class="col-sm-10 col-sm-offset-1">

						<div class="login-container">


							<div class="space-6"></div>

        <div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Enter Your Information for Recovery Password
											</h4>

											<div class="space-6"></div>


											<form  method="post" name="login">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" id="user_name"  required  name="user_name"  placeholder="User Name" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" id="email" required name="email"  placeholder="Your Email" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													 
													 
													<div class="space"></div>

													<div class="clearfix">
														 

														<button type="submit" id="login" name="login" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-comment-o"></i>
															<span class="bigger-110">Send</span>
														</button>
														
														 
														
													</div> 
												</fieldset>	
												</form>
										

											<div class="social-or-login center">
												<span class="bigger-110"> </span>
											</div>

											<div class="space-6"></div>

										 
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">

											<div>
												<a href="forgot_password_link" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>

											<div> 
												 
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
											 

                                            

										</div><!-- /.widget-main -->


									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->


							</div><!-- /.position-relative -->


						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->

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
		<script src="../assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<!-- page specific plugin scripts -->
		<script src="../assets/js/jquery-ui.min.js"></script>
		<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
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
		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
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
			 //override dialog's title function to allow for HTML titles
				$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
					_title: function(title) {
						var $title = this.options.title || '&nbsp;'
						if( ("title_html" in this.options) && this.options.title_html == true )
							title.html($title);
						else title.text($title);
					}
				}));

				$( "#id-btn-dialog1" ).on('click', function(e) {
					e.preventDefault();

					var dialog = $( "#dialog-message1" ).removeClass('hide').dialog({
						modal: true,
						title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='ace-icon fa fa-check'></i> New College Name</h4></div>",
						title_html: true,
						buttons: [
							{
								text: "Save",
								"class" : "btn btn-inverse",
								click: function() {
									$( this ).dialog( "close" );
								}
							},
							{
								text: "Cancel",
								"class" : "btn btn-primary btn-danger",
								click: function() {
									$( this ).dialog( "close" );
								}
							}
						]
					});

			});
		</script>
	</body>
</html>

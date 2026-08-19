<?php

@ob_start();
 include('db/connection.php');   
include('head_index.php');
session_start();
$user1=$_SESSION['SESS_USER_NAME']; 
//echo $user1;
//session_destroy();
include('sidebar.php');
//ob_end_flush();
 
?>
<head>
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />

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
		<?php //include('setting/headernav1.php'); ?>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>
               

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

		<div class="main-content">
					<div class="page-content">
	                   <?php //include('setting/settingpage.php'); ?>
					  <!-- /.ace-settings-container -->
                      
							<div class="page-header">
							
							
							
						</div>
            <div class="row">
					    
					<div class="col-sm-12">
						<div class="">
					
							<div class="position-relative">
								<!-- /.login-box -->
	<?php
$error = "";


$user1 = $_SESSION['SESS_USER_NAME'] ?? null;

if (!$user1) {
    header("Location: login.php");
    exit;
}

// Fetch user's current hashed password
$pass1 = null;
$query = "SELECT password FROM users WHERE user_name = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $user1);
    mysqli_stmt_execute($stmt);
    $result1 = mysqli_stmt_get_result($stmt);

    if ($row1 = mysqli_fetch_assoc($result1)) {
        $pass1 = $row1['password'];
    }

    mysqli_stmt_close($stmt);
}

// When the form is submitted
if (isset($_POST['save'])) {
    $Current = $_POST['Current'];
    $New = $_POST['New'];
    $Confirm = $_POST['Confirm'];

    // Check if current password matches
    if ($pass1 && password_verify($Current, $pass1)) {

        if ($New === $Confirm) {
            // Check password strength
            if (preg_match('#^(?=.*[A-Za-z])(?=.*\d)(?=.*\W).{8,}$#', $New)) {

                // Check if new password is same as current
                if (password_verify($New, $pass1)) {
                    $error = "<p class='red'>New password cannot be the same as the current password.</p>";
                } else {
                    // Hash new password
                    $hashedNew = password_hash($New, PASSWORD_DEFAULT);

                    // Update password and optionally reset status
                    $query = "UPDATE users SET password = ?, status = 1 WHERE user_name = ?";
                    if ($stmt = mysqli_prepare($conn, $query)) {
                        mysqli_stmt_bind_param($stmt, "ss", $hashedNew, $user1);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        $error = "<p class='green'>Password changed successfully.</p><a href='login' class='red'>Click to Login</a>";
                        session_unset();
                        session_destroy();
                        exit;
                    } else {
                        $error = "<p class='red'>Error updating password.</p>";
                    }
                }

            } else {
                $error = "<p class='red'>Password is too weak. Must be at least 8 characters, include a letter, number, and symbol.</p>";
            }

        } else {
            $error = "<p class='red'>New and Confirm Password do not match.</p>";
        }

    } else {
        $error = "<p class='red'>Current password is incorrect.</p>";
    }
}
?>


								<!-- /.forgot-box -->

								<div id="" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h5 class="header black lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												ናይ ይሕለፍ ቃል ምቅያር
											</h5>
	                    <form  method="post" >
												<fieldset>
												
														
													<div class="col-sm-3">
													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle red">
															</span> ህልዉ ናይ ይሕለፍ ቃል

														
													</label> </div>
													<div class="col-sm-9">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" pattren="" required name="Current" id="Current" class="form-control-input-icon" placeholder="Current Password" />

														</span>
													</label></div><br>
													<div class="col-sm-3">
													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle red ">
															 
														</span> ሓዱሽ ናይ ይሕለፍ ቃል 
													</label></div>
											
													<div class="col-sm-9">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password"  required name="New" id="New" class="form-control-input-icon" placeholder=" New Password" />
												
														</span>
													</label></div><br>
													
						
							
													<div class="col-sm-3">
													

													<label class="block clearfix">
														<span class="ace-icon fa fa-info-circle red">
															
														</span>  ምድጋም/ምርግጋፅ ሓዱሽ ናይ ይሕለፍ ቃል 
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="Confirm" id="Confirm" required class="form-control-input-icon" placeholder="Confirm Password" />

														</span>
													</label></div><br>
													<div class="col-sm-3">
													

													<label class="block clearfix"> 
															
														</span>  
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix"> 
														 <?php echo "<p class='red'>$error</p>"; ?>
														 
													</label></div>
													 <div class="col-sm-3">
													

													<label class="block clearfix"> 
															
														</span>  
													</label></div>
													<div class="col-sm-9">

													<label class="block clearfix"> 
														  		  <button type="save" class="width-15 ace-icon fa fa-save bigger-120 blue" id="save" name="save" >&nbsp;&nbsp;ቀይር</button>
													
	   <button type="reset" class="width-15 ace-icon fa fa-refresh bigger-140 red  "id="clear" name="clear" >&nbsp;&nbsp;ኣፅሪ</button>
														 
													</label></div>
												</fieldset>
											</form>
												

											
										</div>

										
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div>
				<br><br><br><br><br><br>
<br><br><br><br><br><br>	<!-- /.main-content -->
</div></div>
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
		<script src="assets/js/jquery.colorbox.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ኣውራ ገፅ - ምሕደራ ስርዓት ሓበሬታ መዘጋጃቤታዊ እቶት</title>
<link rel="icon" type="image/png" href="img/tg.jpg"/>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="slideshow/imageslider.css" rel="stylesheet" type="text/css" />
<script src="slideshow/imageslider.js" type="text/javascript"></script>
<link href="aa.css" rel="stylesheet" type="text/css" media="screen" />
<script src="aa.js" type="text/javascript"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
		
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

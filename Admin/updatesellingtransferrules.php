<?php include('setting/header.php'); ?>
<?php include('../db/connection.php');
$username=$_SESSION['SESS_USER_NAME'];
            $useridad=$_SESSION['SESS_ID'];	
            include("../logactivity.php");
//$letter_folders1="Letters";	 ?>
 <head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
	 
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
																	
																	 <?php
	 //include('connection.php'); 
	$idd=$_GET['idd'];
	$result = mysqli_query($conn,"SELECT * FROM sellingtransferrules where id='$idd'");
							while($row=mysqli_fetch_array($result)){
							//for($i=0; $row = $result->fetch(); $i++){
?>
<form action="" method="post">
<div id="ac">
<!--<input type="hidden" name="id" value="<?php echo $id; ?>" />-->

<span>ዓ/ግልጋሎት: </span><input  type="text" name="mainservice"  value="<?php echo $row['mainservice']; ?>" id="lform" /><br>
<br>                                               
<span>ገዛ ዝዓርፈሉ: </span><input  type="text" name="homelanded" value="<?php echo $row['homelanded']; ?>" id="lform" /><br>
<br>
<span>ዓይነት ገዛ: </span><input  type="text" name="home_type" value="<?php echo $row['home_type']; ?>" id="lform" /><br>
<br>
<span>ደረጃ ቦታ: </span><input  type="text" name="levelofplace"  value="<?php echo $row['levelofplace']; ?>" id="lform" /><br>
<br>
<span>ነፀላ ዋጋ ሜ/ካ: </span><input  type="text" name="singleamount"  value="<?php echo $row['singleamount']; ?>" id="lform" /><br>
<br>
 <div class="form-group">
										
										
									&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;	 &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 	 &nbsp;
							<button type="submit"  name="update" class="btn btn-white btn-info btn-bold">
												<i class="ace-icon  bigger-120 orange"></i>
												ኣስተካክል
											</button> 
											  </div> 


</div>
	</form>
	<?php
	}//}
	?>
	<?php 

	// configuration
	//include('connection.php');

	// new data
	if (isset($_POST['update'])) {
    // Sanitize and retrieve data from the form
    //$idd = $_GET['id'];  // make sure your form includes <input type="hidden" name="id" value="...">
    $mainservice = $_POST['mainservice'];
    $homelanded = $_POST['homelanded'];
    $home_type = $_POST['home_type'];
    $levelofplace = $_POST['levelofplace'];
    $singleamount = $_POST['singleamount'];

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE sellingtransferrules 
                            SET mainservice = ?, homelanded = ?, home_type = ?, levelofplace = ?, singleamount = ? 
                            WHERE id = ?");
    $stmt->bind_param("ssssdi", $mainservice, $homelanded, $home_type, $levelofplace, $singleamount, $idd);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Values Successfully Updated</div>";
//  Record log activity
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
   insertLog($conn, $useridad, $username, "Updated", "Rules for selling, transfer, inheritane and gift are Updated Successfully.", "success", $ip, $agent);   
	echo '<meta content="1;sellingtransferrules" http-equiv="refresh" />';
	}
	else{
	$error="Something is wrong . Please try again.";    
	} 
	//echo 'error';
	//echo' <meta content="1;activatenewmembers" http-equiv="refresh" />';
	//header("location: con_type");
	}
	?>




									 <?php  //include('slider.php');?>
									 	 <?php //include('thumbnail.php'); ?>
										
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
		<script src="../assets/js/jquery.colorbox.min.js"></script>

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
		
		<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<script src="lib/jquery.js" type="text/javascript"></script>-->
<script src="src/facebox.js" type="text/javascript"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<!--<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>-->
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script

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

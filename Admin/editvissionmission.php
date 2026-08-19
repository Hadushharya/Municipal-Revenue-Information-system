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
    $idd = $_GET['vmid'];

    // Prepare the SQL query with a placeholder for the id
    $q = "SELECT * FROM visionmission WHERE id = ?";

    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $q)) {
        // Bind the id parameter to the prepared statement
        mysqli_stmt_bind_param($stmt, "i", $idd); // "i" indicates that id is an integer

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Get the result of the query
        $result = mysqli_stmt_get_result($stmt);

        // Loop through the results
        while ($row = mysqli_fetch_array($result)) {
?>

<form action="" method="post">
<div id="ac">
										<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1">ዓይነት:  </label>
										<input  type="text" name="type" readonly value="<?php echo $row['type']; ?>" id="lform" />
										</div> 
                                   <div class="form-group">
								  <label class="col-sm-2 control-label no-padding-right" for="form-field-1">ራእይ/ልእክቶ/ክብርታት:  </label>
										<textarea id="form-field-1" name="textvissionmission" value="<?php echo $row['textvissionmission']; ?>" rows="8" cols="50">
                                             <?php  echo $row['textvissionmission']; ?>
                                                </textarea>
									    </div> 
										
										
										 <div class="form-group">
										
										
									&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;	 &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 	 &nbsp;
							<button type="save"  name="save" class="btn btn-white btn-info btn-bold">
												<i class="ace-icon  bigger-120 orange"></i>
												ኣስተካክል
											</button> 
											  </div> 


		</div>
		</form>
		<?php
		}}
		?>
		<?php 

		// configuration
		//include('connection.php');

		// new data
		if(isset($_POST['save'])){

		//$id = $_POST['id'];
		$type = $_POST['type'];
		$textvissionmission = $_POST['textvissionmission'];
		



$stmt = mysqli_prepare($conn, "UPDATE `visionmission` SET `type` = ?, `textvissionmission` = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssi", $type, $textvissionmission, $idd);
$query = mysqli_stmt_execute($stmt);

if ($query) {
    echo "Updated Successfully ";
//  Record log activity
				$ip = $_SERVER['REMOTE_ADDR'];
				$agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
			   insertLog($conn, $useridad, $username, "Updated", " Value Updated Successfully.", "success", $ip, $agent);
echo' <meta content="2;vissionmission" http-equiv="refresh" />';
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

<?php
@ob_start();
 include('db/connection.php');   
include('head_index.php'); 
session_start();
session_destroy();
//include('time_now.php');
include('sidebar.php');
ob_end_flush();
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
 
			<div class="main-content">
			


					 
  								<div class="col-xs-12">
								
																	<div>
									<div class="container mt-5 mb-5">
  

<!-- Developer 1: Hadush -->
<div class="card mb-4 shadow-sm" style="border: 4px solid #007bff;">
  <div class="row g-0">
    <div class="col-md-3 text-center p-3">
      <img src="img/people.png" class="img-fluid rounded-circle" alt="Hadush Photo" style="max-width: 150px;">
    </div>
    <div class="col-md-9">
      <div class="card-body">
        <h4 class="card-title text-primary">Hadush Harya</h4>
        <p><strong>Qualification:</strong> MSc in Information Security and Digital Forensics</p>
        <p><strong>Phone:</strong> +251-914507935</p>
        <p><strong>Email:</strong> yfterelu23@gmail.com</p>
       
      
      </div>
    </div>
  </div>


<!-- Developer: Hagos -->
<div class="card mb-4 shadow-sm">
  <div class="row g-0">
    <div class="col-md-3 text-center p-3">
      <img src="img/people.png" class="img-fluid rounded-circle" alt="Hagos Photo" style="max-width: 150px;">
    </div>
    <div class="col-md-9">
      <div class="card-body">
        <h4 class="card-title text-primary">Hagos Hailemaryam</h4>
        <p><strong>Qualification:</strong> MSc in Information Technology</p>
        <p><strong>Phone:</strong> +251-946258092</p>
        <p><strong>Email:</strong> 21hagos21@gmail.com</p>
      
      
      </div>
    </div> 
	
	<div class="col-md-9">
      <div class="card-body">
        	<blockquote class="blockquote mt-1">
          <p class="mb-0">"Alone we can do so little; together we can do so much.
              Helen Keller”</p>
        </blockquote>
      
      
      </div>
    </div>
	
	

  </div>
</div>
 
<!-- Footer Message -->
<div class="alert alert-success text-center mt-4">
  Segen IT Solutions PLC.
</div></div>

								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						 
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

<?php include('setting/header.php'); ?>
<?php include('connection.php');

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
	$idd=$_GET['id'];
	$result = mysql_query("SELECT * FROM household where id='$idd'");
							while($row=mysql_fetch_array($result)){
							//for($i=0; $row = $result->fetch(); $i++){
?>
<form action="" method="post">
<div id="ac">
<!--<input type="hidden" name="id" value="<?php echo $id; ?>" />-->
<span>ተ/ቁ: </span><input readonly type="text" name="no" value="<?php echo $row['no']; ?>" id="lform" /><br>
<br>
<span>መፍለይ ቁፅሪ: </span><input readonly type="text" name="id" value="<?php echo $row['id']; ?>" id="lform" /><br>
<br>
<span>ሽም ምስ ኣባሓጎ : </span><input type="text" name="name" value="<?php echo $row['name']; ?>" id="lform" /><br>
<br>
<span> ፆታ </span><input type="text" name="sex" id="lform" value="<?php echo $row['sex']; ?>" /><br>
<br>
<span>ዕድመ: </span><input type="text" name="age" id="lform" value="<?php echo $row['age']; ?>"/><br>
<br>
<span>ደረጃ ትምህርቲ: </span><input type="text" name="academic_rank" value="<?php echo $row['academic_rank']; ?>" id="lform" /><br>
<br>
<span>ዞባ : </span><input type="text" name="zone" value="<?php echo $row['zone']; ?>" id="lform" /><br>
<br>
<span>ወረዳ : </span><input type="text" name="woreda" value="<?php echo $row['woreda']; ?>" id="lform" /><br>
<br>
<span>ቀበሌ/ጣብያ : </span><input type="text" name="kebele" value="<?php echo $row['kebele']; ?>" id="lform" /><br>
<br>
<span> ተባ: </span><input type="text" name="male" value="<?php echo $row['male']; ?>" id="lform" /><br>
<br>
<span>ኣን:</span><input type="text" name="female" value="<?php echo $row['female']; ?>" id="lform" /><br>
<br>
<span>ድምር : </span><input type="text" name="total" value="<?php echo $row['total']; ?>" id="lform" /><br>
<br>
<span>መዕቖቢ : </span><input type="text" name="idp" value="<?php echo $row['idp']; ?>" id="lform" /><br>
<br>
<span>ኩነታት : </span><input type="text" name="status" value="<?php echo $row['status']; ?>" id="lform" /><br>
<br>
<span>መብርሂ : </span><input type="text" name="remark" value="<?php echo $row['remark']; ?>" id="lform" /><br>

<br><input type="submit" name="update" value="Save Change" />
</div>
</form>
<?php
}//}
?>
<?php 

// configuration
//include('connection.php');

// new data
if(isset($_POST['update'])){

//$id = $_POST['id'];
$name = $_POST['name'];
$sex = $_POST['sex'];
$age = $_POST['age'];
$academic_rank = $_POST['academic_rank'];
$zone = $_POST['zone'];
$woreda = $_POST['woreda'];
$kebele = $_POST['kebele'];
$male = $_POST['male'];
$female = $_POST['female'];
$total = $_POST['total'];
$idp = $_POST['idp'];
$status = $_POST['status'];
$remark = $_POST['remark'];

$query=mysql_query("UPDATE `household` SET `name`='$name',`sex`='$sex',`age`='$age',`academic_rank`='$academic_rank',`zone`='$zone',`woreda`='$woreda',`kebele`='$kebele',`male`='$male',`female`='$female',`total`='$total',`idp`='$idp',`status`='$status',`remark`='$remark' WHERE  id='$idd'");
if($query)
{
echo "updated ";
}
else{
$error="Something is wrong . Please try again.";    
} 
echo' <meta content="1;activatenewmembers" http-equiv="refresh" />';
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

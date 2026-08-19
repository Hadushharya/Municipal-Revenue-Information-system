<?php include('setting/header.php'); ?>
<?php include('../db/connection.php');

    $userid=$_SESSION['SESS_ID'];	 ?>
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
		<center>														<?php
	include('../db/connection.php');  
 ?>
 <html>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<script src=".../argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<script src="js/jquery.js"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<meta charset="utf-8">
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
       
	   <div class="row">
									<div class="col-xs-12"> 
											<!-- PAGE CONTENT BEGINS -->
											
														<?php  //include('setting/menu.php'); ?>
														
														 
								<h4 class="lighter">
									
										 
										 <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
										 <fieldset>
										 <legend>ናይ ከተማ ሽረ እ/ስላሴ ማዛጋጃቤታዊ እቶት ናይ ግልጋሎት ማዓልታዊ መፅናዕቲ  <?php   $year=date('Y',strtotime('now'));
										    echo $year-8 ?>  ዓ/ም</legend>
									      <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ሽም ምስ ኣባሓጎ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="fullname"  required id="fullname" placeholder=" ሽም ምስ ኣባሓጎ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ቀበሌ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="kebele"  required id="kebele" placeholder="ቀበሌ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ብሎክ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="block"  required id="block" placeholder="ብሎክ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div> 
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ኣዋሳኒ:  </label>
									    </div> 
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምብ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="east"  required id="east" placeholder="ምብ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምዕ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="west"  required id="west" placeholder="ምዕ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ሰሜ:  </label>
										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="north"  required id="north" placeholder="ሰሜ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ደቡ:  </label>
										<div class="col-sm-10">
											 <input type="text" id="form-field-1"  name="south"  required id="south" placeholder="ደቡ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ፋ/ቁፅሪ:  </label>
                                           <div class="col-sm-10">
											<input type="text" id="form-field-1"  name="filenumber"  required id="filenumber" placeholder="ፋ/ቁፅሪ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>

                                 <div class="form-group">
								  <label class="col-sm-2 control-label no-padding-right" for="form-field-1">ደረጃ ቦታ:  </label>
										<select  class="col-xs-9 col-sm-1"  id="form-field-1"  required  name="levelofplace" id="levelofplace">
													<option value=""> ምረፅ</option>
													<option value="1ይ1ይ"> 1ይ1ይ</option>
													<option value="1ይ2ይ">1ይ2ይ </option>	
                                                    <option value="1ይ3ይ">1ይ3ይ </option>	
                                                    <option value="2ይ1ይ"> 2ይ1ይ</option>
													<option value="2ይ2ይ">2ይ2ይ </option>	
                                                    <option value="2ይ3ይ">2ይ3ይ </option>	
                                                    <option value="3ይ1ይ"> 3ይ1ይ</option>
													<option value="3ይ2ይ">3ይ2ይ </option>	
                                                    <option value="3ይ3ይ">3ይ3ይ </option>													
												</select>
									    </div> 																							
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ዝተታሓዘሉ እዋን:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="occupiedyear"  required id="occupiedyear" placeholder="ዝተታሓዘሉ እዋን" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										
										 <div class="form-group">
								  <label class="col-sm-2 control-label no-padding-right" for="form-field-1">ዓ/ግልጋሎት:  </label>
										<select  class="col-xs-9 col-sm-1"  id="form-field-1"  required  name="mainservice" id="mainservice">
													<option value=""> ምረፅ</option>
													<option value="መንበሪ">መንበሪ</option>
													<option value="ድርጅት">ድርጅት </option>	
                                                    <option value="ሕዉስዋስ">ሕዉስዋስ </option>						
												</select>
									    </div>
										<div class="form-group">
								  <label class="col-sm-2 control-label no-padding-right" for="form-field-1">ናይቲ መሬት ዓይነት ትሕዝቶ:  </label>
										<select  class="col-xs-9 col-sm-1"  id="form-field-1"  required  name="kindofvolumeofland" id="kindofvolumeofland">
													<option value=""> ምረፅ</option>
													<option value="ክራይ"> ክራይ</option>
													<option value="ሊዝ">ሊዝ </option>	
												</select>
									    </div>
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ስፍሓት ብካ/ሜ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="area"  required id="area" placeholder="ስፍሓት ብካ/ሜ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ምእሳር ውዕል:  </label><br/>
										</div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ህንፀት መጀመሪ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="beginofconstruction"  required id="beginofconstruction" placeholder="ህንፀት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ህንፀት መወድኢ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="endofconstruction"  required id="endofconstruction" placeholder="ህንፀት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										<div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ክፍሊት መጀመሪ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="beginpayment"  required id="beginpayment" placeholder="ክፍሊት መጀመሪ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										 <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ክፍሊት መወድኢ:  </label>
										<div class="col-sm-9">
											<input type="text" id="form-field-1"  name="endpayment"  required id="endpayment" placeholder="ክፍሊት መወድኢ" class="col-xs-10 col-sm-5" />
									 	 </div>
									    </div>
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> ዝከፍሎ  መጠን ገንዘብ:  </label>

										<div class="col-sm-10">
											<input type="text" id="form-field-1"  name="amountofpay"  required id="amountofpay" placeholder="ዝከፍሎ  መጠን ገንዘብ" class="col-xs-10 col-sm-5" />
									 	</div>
									    </div>
										
										 <div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1" >   </label>

										  <div class="col-sm-10">
										   <div class="form-group">
															<div class="col-xs-5">
															<center>
											 <p>
											
										  <button type="save"  name="save" class="btn btn-white btn-info btn-bold">
												<i class="ace-icon fa fa-plus bigger-120 orange"></i>
												መዝግብ
											</button> &nbsp;&nbsp;&nbsp;&nbsp;

											<button type="reset" class="btn btn-white btn-default btn-round">
												<i class="ace-icon fa fa-times red2"></i>
												ኣፅሪ
											</button>
										</p>  </center>
														</div>
														</div>
													 	 
														</div>
														
											  	
									          </div>
											   </fieldset>
										  </form>	
										  <?php
										  

if(isset($_POST['save'])){
$fullname=$_POST['fullname'];
$kebele=$_POST['kebele'];
$block=$_POST['block'];
$east = $_POST['east'];
$west=$_POST['west'];
$north=$_POST['north'];
$south = $_POST['south'];
$filenumber=$_POST['filenumber'];
$levelofplace=$_POST['levelofplace'];
$occupiedyear=$_POST['occupiedyear'];
$mainservice=$_POST['mainservice'];
$kindofvolumeofland=$_POST['kindofvolumeofland'];
$area=$_POST['area'];
$beginofconstruction=$_POST['beginofconstruction'];
$endofconstruction=$_POST['endofconstruction'];
$beginpayment=$_POST['beginpayment'];
$endpayment=$_POST['endpayment'];
$amountofpay=$_POST['amountofpay'];
$query=mysqli_query($conn, "INSERT INTO `clientslanddata`(`id`, `fullname`, `kebele`, `block`, `east`, `west`, `north`,
`south`, `filenumber`, `levelofplace`, `occupiedyear`, `mainservice`, `kindofvolumeofland`, `area`,`beginofconstruction`,
`endofconstruction`, `beginpayment`, `endpayment`, `amountofpay`)

VALUES (NULL,'$fullname','$kebele','$block','$east','$west','$north','$south','$filenumber',
'$levelofplace','$occupiedyear','$mainservice','$kindofvolumeofland','$area','$beginofconstruction','$endofconstruction','$beginpayment','$endpayment','$amountofpay')");
//$query=mysqli_query($conn,"INSERT INTO admin (id,fname,mname,lname,user_name,password,type) VALUES (NULL,'$fname,$mname,$lname,$user_name,$password,$type')");
if($query)
{
echo'<p class="success" style="color:#390"> Client successfully Registered</p>';                                
		   echo' <meta content="6;newlandregistration" http-equiv="refresh" />';
}
else{
echo'<P style="color:red" > Error, Already Registered Client</p>'; 
}
}
										
?>		
														
										 
								
												
									
									
								</h4>	<div class="hr hr-18 hr-double dotted"></div>
								
								
						</div><!-- /.row -->
						
						<div class="row">
						
						
						
						
								</div >
                               </div > 								
								
 
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

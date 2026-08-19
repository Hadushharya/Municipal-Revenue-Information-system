<?php include('../db/connection.php'); 
?>
<?php  //include('../header_baner.php'); ?>
	<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="index" class="navbar-brand">
						<i class="fa fa-home"><font size="4"> </font></i>
							
					</a>
				</div>
			 
				<div class="navbar-header pull-center">
					<a href="index" class="navbar-brand">
						<i class="fa fa-Red"><font size="4">
						 
						<?php
				 
			 /* 
						 $id=$_SESSION['SESS_ID']; 

																				$result=mysqli_query($conn, "SELECT * FROM `admin` WHERE id='$id'");
																				if (mysqli_num_rows($result) > 0) 
																				{
																				while($row = mysqli_fetch_assoc($result))
																				{ $fname1= $row['fname'];
																				 $mname1=$row['mname']; 
																				 //$title=$row['title'];
																				// echo "      ".$title." ".$fname1."  ".$mname1;
																				}
																				} */
													 
										 
						?>
						
						</font></i>
							
					</a>
				</div>
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important"> </span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-exclamation-triangle"></i>
							   Notifications
								</li>

								 

								<li class="dropdown-footer">
									<a href="inbox">
										See all notifications
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								
							<span class="user-info">
									<small>Welcome,</small>
									
							
									<?php
									 $userid=$_SESSION['SESS_ID'];
										$result=mysqli_query($conn, "SELECT  *FROM `users`  WHERE id='$userid'");
										if (mysqli_num_rows($result) > 0) 
										{
										while($row = mysqli_fetch_assoc($result))
										{ $fname1= $row['fname'];
										 $mname1=$row['mname'];
										 //$title=$row['title'];
												 
                                      echo  $fname1.' '.$mname1;													
										}
										} ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								

							<li class="divider"></li>

								<li class="divider"></li>

									<li>
									<a href="setting_pass">
									
										<i class="ace-icon fa fa-lock pink"></i>
										ምቕያር ናይ ባዕልኻ ናይ ይሕለፍ/Change Password
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="../index?categ=all">
									
										<i class="ace-icon fa fa-power-off red"></i>
										ዕፆ/Logout
									</a>
								</li><li class="divider"></li>
								 
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
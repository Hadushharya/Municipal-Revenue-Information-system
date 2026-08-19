 
          
		<?php include('db/dbcon.php'); ?>
 <?php
 $file_idv=0;
 $file_ida=0;
 $file_idi=0;
 $file_idd=0;
 $ad=0;
 $ve=0;
 $im=0;
 $do=0; 
											   
												$sql="SELECT file_id FROM file where type='video'";
											if ($result=mysqli_query($con ,$sql))
													  {
													  // Return the number of rows in result set
													  $ve=mysqli_num_rows($result);
												
													  }
												 
												 
 $result=mysqli_query($con, "SELECT file_id FROM file where type='video' ORDER BY file_id ASC LIMIT 1;");
									    if (mysqli_num_rows($result) > 0) 
										{
										while($row = mysqli_fetch_assoc($result))
										{ $file_idv= $row['file_id'];
											
											 
										}
										}
											$sql="SELECT file_id FROM file where type='audio'";
											if ($result=mysqli_query($con ,$sql))
													  {
													  // Return the number of rows in result set
													  $ad=mysqli_num_rows($result);
												
													  }
										 $result=mysqli_query($con, "SELECT file_id FROM file where type='audio' ORDER BY file_id ASC LIMIT 1;");
									    if (mysqli_num_rows($result) > 0) 
										{
										while($row = mysqli_fetch_assoc($result))
										{ $file_ida= $row['file_id'];
											
											 
										}
										}
										$sql="SELECT file_id FROM file where type='documents'";
											if ($result=mysqli_query($con ,$sql))
													  {
													  // Return the number of rows in result set
													  $do=mysqli_num_rows($result);
												
													  }
													  $sql="SELECT file_id FROM file where type='Images'";
											if ($result=mysqli_query($con ,$sql))
													  {
													  // Return the number of rows in result set
													  $im=mysqli_num_rows($result);
												
													  }
										 $result=mysqli_query($con, "SELECT file_id FROM file where type='documents' ORDER BY file_id ASC LIMIT 1;");
									    if (mysqli_num_rows($result) > 0) 
										{
										while($row = mysqli_fetch_assoc($result))
										{ $file_idd= $row['file_id'];
											
											 
										}
										}
										?>
 <hr> 
      <a href="list_image?categ=images"><button class="btn btn-success"><i class=" icon fa fa-folder-open white"></i>  Images(<?php echo $im; ?>)</button></a>
	   <a href="a?id=<?php echo $file_ida;?>"><button class="btn btn-success"><i class=" icon glyphicon glyphicon-music  white"></i>  Music(<?php echo $ad; ?>)</button></a>
           <a href="v?id=<?php echo $file_idv;?>"><button class="btn btn-primary" id="clickme"><i class="icon fa fa-video-camera white "></i>    Videos(<?php echo $ve; ?>)</button></a>
           <a href="d?id=<?php echo $file_idd;?>"><button class="btn btn-info"><i class="icon fa fa-file white"></i>    Documents(<?php echo $do; ?>)</button></a>
 
           <hr>
           
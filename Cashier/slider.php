		       
                 <div>
						<ul class="ace-thumbnails clearfix">
									<?php
								
								 
                                $q="SELECT * FROM `file`  where type='images' ORDER BY RAND()";
                            $i=0;
                            $result=mysqli_query($con,$q);
								 
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['file_id'];
								$i+=1;

								if($i<5){
								?>
							<div class="col-sm-6">
							  	<li style="background-color:white; color:white margin:10px; padding:10px; border:3px solid blue;">
								<a href="page_setting/Image/<?php echo $row['path'];?>" title="<?php echo $row['title'];?>" data-rel="colorbox">
								<img width="500" height="230" alt="150x150" src="page_setting/Image/<?php echo $row['path'];?>" />
								</a>
                                 </li>
							</div>
                             <?php 
							          }
							     } 
							 ?>
						 </ul>
								</div>
								
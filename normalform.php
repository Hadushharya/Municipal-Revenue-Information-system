<form method="POST" enctype="multipart/form-data">
				<div class="form-group"> 
					
				</div>
				
				<center><h3>ዝርዝር ንብረት  ተገልገልቲ  </h3></center>
				
				
				
					<div id="dt">
										  <thead> 
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										</thead> 
										<div  class="table-header">
											Results 
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div id="tablecon"> 
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
													<tr>
														<th class="center">
															<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														
														<th class='blue'>ሙሉእ ሽም</th>
														<th class='blue'> ግብሪ ዓመት(ዓ/ም)</th>
														<th class='blue'>መጠን ክፍሊት</th>
														<th class='blue'> ዝከፈለሉ   ቅብሊት	</th>	
														<th class='blue'>ተግብር</th>
													</tr>
												</thead>

										
												<tbody>
															 <?php
								 $i=0;
								//$cont_id=0;
								 
								//$userid=$_SESSION['SESS_ID'];
								 
                                $q="SELECT * FROM `landpayment` where fullname='$fullname'";
                            $i=0;
                            $result=mysqli_query($conn,$q);
								 
								while ($row= mysqli_fetch_array($result) ){
								$id=$row['id'];
								 
									$i++;	 

											
									?>
													<tr>
														<td class="center">
															<label class="pos-rel">
																<?php echo $i; ?>
																<span class="lbl"></span>
															</label>
														</td>

														
														
														<td class="hidden-480"><?php echo $row['fullname']; ?></td>
														<td> <?php	echo $row['year'];	  ?> </td>
														<td> <?php echo $row['amount']; ?> </td>
														<td><?php echo $row['receipt']; ?></td>

														<td>
															<div class="hidden-sm hidden-xs action-buttons">
															
																<a class="blue" href="view_listofmaterials<?php  //echo '?acc='.$con_codee; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																	<i class="ace-icon fa fa-pencil-square-o bigger-130"></i>
																</a>

																<a class="red" href="delete_listofmaterials<?php  //echo '?acc='.$con_codee; ?>" class="tooltip-success" data-rel="tooltip" title="Delete">
																	<i class="ace-icon fa fa-trash bigger-130"></i>
																</a>

																 
															</div>

															
														</td>
													</tr>
 
													 <?php } ?>
												</tbody>
											</table>
										</div>
										</div>
									</div>
									</form>
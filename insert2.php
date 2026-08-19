
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" encoding="UTF-8">
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ኣውራ ገፅ-Tigray defence force</title>
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('a[rel*=facebox]').facebox({
      loadingImage : 'src/loading.gif',
      closeImage   : 'src/closelabel.png'
    })
  })
</script>
<?php
session_start();
include('connection.php');
if(isset($_POST['Submit'])){
$aa=$_POST['id'];
$a = $_POST['name'];
$b = $_POST['age'];
$c = $_POST['gender'];
$d = $_POST['level'];
$f = $_POST['subject'];
$g = $_POST['title'];
$h = $_POST['date'];
$i = $_POST['month'];
$j = $_POST['year'];		
// query
$query=mysqli_query($conn,"INSERT INTO tes (id,name,age,gender,level,subject,title,date,month,year) VALUES ('$aa','$a','$b','$c','$d','$f','$g','$h','$i','$j')");
if($query)
{
echo'<p class="success" style="color:#390"> Account is created successfully</p>';                                
		   echo' <meta content="6;fortest.php" http-equiv="refresh" />';
}
else{
echo'<P style="color:red" > Error Already Registered with this employee ID</p>'; 
}
}
?>
</html>
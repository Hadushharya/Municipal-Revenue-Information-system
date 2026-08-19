<?php 


  /* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
/* $link = mysqli_connect("localhost", "root", "", "dbname");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Attempt create database query execution
$sql = "CREATE DATABASE IF NOT EXISTS dbname";
if(mysqli_query($link, $sql)){
   // echo "Database created successfully";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);

 $conn=mysqli_connect("localhost", "root", "", "dbname");
 if($conn)
 { 
  //include('Create_DB_file.php'); 
 }
 else  {
 

 echo "<script>
window.location ='db/unconfigdatabase.php'
</script>";
 
 }
 $query=mysql_query("SET CHARACTER SET utf8") or die('Cannot select CHARACTER SET utf8: ' . mysql_error());  */
?>
<?php
 // config file used to connect to data base.
 // connection to database.
 $conn = mysqli_connect("localhost","root","");
 // if database is not found.
 //$query=mysqli_query($conn,"SET CHARACTER SET utf8") or die('Cannot select CHARACTER SET utf8: ' . mysqli_error());
 // selection of database.
 mysqli_select_db($conn, "revenuedatabase");
?>
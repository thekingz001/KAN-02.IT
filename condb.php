<?php
// DB from Vansale
$con= mysqli_connect("kns-database21.cu4s2ibvwwoe.ap-southeast-1.rds.amazonaws.com:3306","","","") or die("Error: " . mysqli_error($con));
mysqli_query($con, "SET NAMES 'utf8' ");
error_reporting( error_reporting() & ~E_NOTICE ); 

?>

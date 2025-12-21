<?php
$conn = new mysqli('localhost','root','','users');
   if($conn->connect_errno){
      echo $conn->connect_errno.": ".$conn->connect_error;
   }
 ?>

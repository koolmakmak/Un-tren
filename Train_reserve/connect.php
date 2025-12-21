<?php
$conn = new mysqli('localhost','root','','train_reservation_system');
   if($conn->connect_errno){
      echo $conn->connect_errno.": ".$conn->connect_error;
   }
 ?>

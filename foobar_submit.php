<?php
	$ip="";
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
  
  echo "IP Address: ";
  echo $ip;
  echo "<br><br>";
  echo "Username: ";
  echo $_POST['Sign_in_name'];
  echo "<br><br>";
  echo "Password: ";
  echo  $_POST['Password'];
  echo "<br><br>";
  print_r($_POST); 
?>
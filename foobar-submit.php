<?php
  echo Username: $_POST['Sign_in_name'];
  echo "<br><br>";
  echo Password: $_POST['Password'];
  echo "<br><br>";
  echo Rem_Addr: $_SERVER['REMOTE_ADDR']
  echo "<br><br>";
  echo CLIENTIP: $_SERVER['HTTP_CLIENT_IP']
  echo "<br><br>";
  print_r($_POST); 
?>
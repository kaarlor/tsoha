<?php
require('db/connection.php');

if ($_COOKIE['username'] && $_COOKIE['password']){
   $username = $_COOKIE['username'];
   $password = $_COOKIE['password'];
}

$result = pg_query($connection, 'SELECT username, id FROM users WHERE username=\''.pg_escape_string($username).'\' AND password=\''.pg_escape_string($password).'\'');

if ($row = pg_fetch_row($result)) {
   setcookie('username',$username,time()+3600);
   setcookie('password',$password,time()+3600);
   $login_username = $row[0];
   $login_id = $row[1];
}
else{
  header('Location: index.php');
  exit;
}

?>
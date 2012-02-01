<?php
require('db/connection.php');

if ((!$_POST['trylogin']) && $_COOKIE['username'] && $_COOKIE['password']){
   $username = $_COOKIE['username'];
   $password = $_COOKIE['password'];
} else if ($_POST['username'] && $_POST['password']){
  $username = $_POST['username'];
  $password = md5($_POST['password']);
}

$result = pg_query($connection, 'SELECT username, id FROM users WHERE username=\''.addslashes($username).'\' AND password=\''.addslashes($password).'\'');

if ($row = pg_fetch_row($result)) {
   setcookie('username',$username,time()+3600);
   setcookie('password',$password,time()+3600);
   $login = true;
   $login_username = $row[0];
   $login_id = $row[1];
}
else {
     header('Location: index.php');
     exit;
}

?>
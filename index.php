<?php

if ($_POST['trylogin'] && $_POST['username'] && $_POST['password']){
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  setcookie('username',$username,time()+3600);
  setcookie('password',$password,time()+3600);
  header('Location: user_home.php');
  exit;
}
if (!$_POST['trylogin']){
  setcookie('username','',time());
  setcookie('password','',time());
}

?>

<html>
 
<?php include('static_elements/head.php'); ?>
  
  <body>
    <h1>Olutp&auml;iv&auml;kirja</h1>
<?php
if ($_POST['trylogin']){
   echo('<div class="error">Virhe: Käyttäjänimi ja salasana eivät täsmää.</div>');
}
?>
    <form method="POST" action="index.php">
      <table>
	<tr>
	  <td>K&auml;ytt&auml;j&auml;nimi</td><td><input name="username" type="textbox"></td>
	</tr>
	<tr>
	  <td>Salasana</td><td><input name="password" type="password"></td>
	</tr>
      </table>
      <input type="hidden" name="trylogin" value="true">
      <input type="submit" value="Kirjaudu">
    </form>
    <a href="new_user.php">Rekister&ouml;idy k&auml;ytt&auml;j&auml;ksi</a>
  </body>
  
</html>

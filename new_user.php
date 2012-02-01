<?php

if ($_POST['newuser']){
   require('db/connection.php');
   if ($_POST['password'] != $_POST['password2']){
      echo('Virhe: Antamasi salasanat eiv&auml;t t&auml;sm&auml;&auml;.');
   } else if ($_POST['password'] == '' || $_POST['username'] == '') {
     echo('Virhe: Et antanut kaikkia tietoja.');
   } else {
     $result = pg_query($connection, 'SELECT * FROM users WHERE username=\''.addslashes($_POST['username']).'\'');
     if (pg_fetch_row($result)){
          echo('Virhe: K&auml;ytt&auml;j&auml;nimi on jo k&auml;yt&ouml;ss&auml;.');
     } else {
       $result = pg_query($connection,
     	     'INSERT INTO users (username, password) VALUES (\''.addslashes($_POST['username']).'\', \''.addslashes(md5($_POST['password'])).'\')');
       if (!$result) {
         echo('Virhe k&auml;ytt&auml;j&auml;&auml; luodessa.');
       } else {
         header('Location: new_user_done.php');
	 exit;
       }
     }
   }
}

?>

<html>
 
  <head>
    <title>Olutp&auml;iv&auml;kirja</title>
  </head>
  
  <body>
    <h1>Uusi k&auml;ytt&auml;j&auml;</h1>
    <form method="POST" action="new_user.php">
      <input type="hidden" name="newuser" value="true">	
      <table>
	<tr>
	  <td>Anna k&auml;ytt&auml;j&auml;nimi</td><td><input name="username" type="textbox"<?php if ($_POST['newuser']) echo(' value="'.$_POST['username'].'"'); ?>></td>
	</tr>
	<tr>
	  <td>Anna salasana</td><td><input name="password" type="password"></td>
	</tr>
	<tr>
	  <td>Toista salasana</td><td><input name="password2" type="password"></td>
	</tr>
      </table>
      <input type="submit" value="Luo uusi k&auml;ytt&auml;j&auml;">
    </form>
    <a href="index.php">Peruuta</a>
  </body>
  
</html>

<html>
 
  <head>
    <title>Olutp&auml;iv&auml;kirja</title>
  </head>
  
  <body>
    <h1>Olutp&auml;iv&auml;kirja</h1>
    <form method="POST" action="user_home.php">
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

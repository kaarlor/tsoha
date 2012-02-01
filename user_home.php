<?php require('handle_login.php'); ?>
<html>

  <head>
    <title>Olutp&auml;iv&auml;kirja</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>

  <body>
    <h1><?php echo($login_username); ?></h1>

    <h2>Lis&auml;&auml; olut</h2>
    <form method="POST" action="handle_add_beer.php">
      <input type="hidden" name="new_beer" value="true">
      <select name="beer_id">
	<option value="0">Uusi olut</option>
	<?php
	   $result = pg_query($connection, 'SELECT beers.id, beers.name, breweries.name FROM beers INNER JOIN breweries ON beers.brewery = breweries.id');
	   while ($row = pg_fetch_row($result)){
	   echo('	      <option value="'.$row[0].'">'.$row[1].' ('.$row[2].')</option>'."\n");
	   }
	   ?>
      </select><br>
      T&auml;yt&auml; kent&auml;t, jos valitsit &quot;Uusi olut&quot;.
      <table>
	<tr>
	  <td>Nimi:</td><td><input type="text" name="new_beer_name"></td>
	</tr>
	<tr>
	  <td>Panimo:</td>
	  <td><input type="text" name="new_beer_brewery_name"><br>
	    tai valitse listasta<br>
	    <select name="new_beer_brewery_id">
	      <option value="0">Uusi panimo</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM breweries');
		 while ($row = pg_fetch_row($result)){
		 echo('	      <option value="'.$row[0].'">'.$row[1].'</option>'."\n");
		 }
		 ?>
	    </select>
	  </td>
	</tr>
      </table>
      <input type="submit" value="Lis&auml;&auml; olut">
    </form>

    <h2>Oluesi</h2>
    <table border=1>
      <tr>
	<th>Olut</th><th>Panimo</th>
      </tr>
<?php
$result = pg_query($connection,
   'SELECT beers.name AS beer, breweries.name AS brewery FROM users INNER JOIN tastings ON users.id = tastings.t_user
   INNER JOIN beers ON tastings.t_beer = beers.id
   INNER JOIN breweries ON beers.brewery = breweries.id
   WHERE users.id = \''.addslashes($login_id).'\' ORDER BY beer ASC');
while ($row = pg_fetch_row($result)){
   echo('<tr><td>'.$row[0].'</td><td>'.$row[1].'</td></tr>'."\n");
}
?>
    </table>
    <a href="handle_logout.php">Kirjaudu ulos</a>
  </body>

</html>

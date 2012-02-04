<?php

require('handle_login.php');

// Lisätään olut, jos kaikki tiedot on kunnossa.

$success = false;
$error = '';
$message = '';

if ($_POST['add_beer']){
   $beer_id = -1;
   $beer_id = intval($_POST['beer_id']);
  if ($beer_id > 0) {
    $result = pg_query($connection, 'SELECT * FROM beers WHERE id = '.pg_escape_string($beer_id));
    if (pg_fetch_row($result)){
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($beer_id).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
      $success = true;
    } else {
      $error = 'Virhe: Virheellinen beer_id.';
    }
  } else if ($beer_id == 0 && trim($_POST['new_beer_name']) != '') {
    $new_beer_brewery_id = -1;
    $new_beer_brewery_id = intval($_POST['new_beer_brewery_id']);
    if ($new_beer_brewery_id > 0){
      $result = pg_query($connection, 'SELECT * FROM breweries WHERE id = '.pg_escape_string($new_beer_brewery_id));
      if (pg_fetch_row($result)){
        pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', '.pg_escape_string($new_beer_brewery_id).')');
        $result = pg_query($connection, 'SELECT currval(\'beers_id_seq\')');
        $row = pg_fetch_row($result);
        pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
        $success = true;
      } else {
         $error = 'Virhe: Virheellinen new_beer_brewery_id.';
      }
    } else if ($new_beer_brewery_id == 0 && trim($_POST['new_beer_brewery_name']) != '') {
      $result = pg_query($connection, 'SELECT * FROM breweries WHERE name = \''.pg_escape_string(trim($_POST['new_beer_brewery_name'])).'\'');
      $row = pg_fetch_row($result);
      if ($row){
      	pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', '.pg_escape_string($row[0]).')');
      	$row = pg_fetch_row(pg_query($connection, 'SELECT currval(\'beers_id_seq\')'));
      	pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
      	$success = true;
      } else {
      	pg_query($connection, 'INSERT INTO breweries (name) VALUES (\''.pg_escape_string(trim($_POST['new_beer_brewery_name'])).'\')');
      	$row_brewery = pg_fetch_row(pg_query($connection, 'SELECT currval(\'breweries_id_seq\')'));
      	pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', '.pg_escape_string($row_brewery[0]).')');
      	$row = pg_fetch_row(pg_query($connection, 'SELECT currval(\'beers_id_seq\')'));
      	pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
      	$success = true;
      }
    } else if ($new_beer_brewery_id == -1) {
      pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', NULL)');
      $row = pg_fetch_row(pg_query($connection, 'SELECT currval(\'beers_id_seq\')'));
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
      $success = true;

    } else {
        $error = 'Virhe: Et antanut panimon nime&auml;.';
    }
  } else {
    $error = 'Virhe: Et antanut oluen nime&auml;.';
  }
if ($success == true)
   $message = 'Olut lis&auml;ttiin onnistuneesti.';
}

?>
<html>

<?php include('static_elements/head.php'); ?>

  <body>
    <h1><?php echo($login_username); ?></h1>

<?php
if ($error)
   echo('<div class="error">'.$error.'</div>');
else if ($message)
     echo('<div class="message">'.$message.'</div>');
?>

    <h2>Lis&auml;&auml; olut</h2>
    <form method="POST" action="user_home.php">
      <input type="hidden" name="add_beer" value="true">
      <select name="beer_id">
	<option value="0">Uusi olut</option>
	<?php
	   $result = pg_query($connection, 'SELECT beers.id, beers.name, breweries.name
	   	   FROM beers LEFT JOIN breweries ON beers.brewery = breweries.id
		   ORDER BY beers.name ASC');
	   while ($row = pg_fetch_row($result)){
	   if ($row[2] != '')
	   	   echo('	      <option value="'.$row[0].'">'.$row[1].' ('.$row[2].')</option>'."\n");
           else
	   	   echo('	      <option value="'.$row[0].'">'.$row[1].'</option>'."\n");
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
	      <option value="-1">-</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM breweries ORDER BY name ASC');
		 while ($row = pg_fetch_row($result)){
		 echo('	      <option value="'.$row[0].'">'.$row[1].'</option>'."\n");
		 }
		 ?>
	    </select>
	  </td>
	</tr>
      </table>
      Kommentteja:<br>
      <textarea name="comment" rows="10" cols="30"></textarea><br>
      <input type="submit" value="Lis&auml;&auml; olut">
    </form>

    <h2>Oluesi</h2>
    <table border=1 class="tasting_table">
      <tr>
	<th>Olut</th><th>Panimo</th><th>Kommentteja</th><th>Poista olut</th>
      </tr>
<?php
$result = pg_query($connection,
   'SELECT beers.id AS id, beers.name AS beer, tastings.comment, breweries.name AS brewery FROM users INNER JOIN tastings ON users.id = tastings.t_user
   INNER JOIN beers ON tastings.t_beer = beers.id
   LEFT JOIN breweries ON beers.brewery = breweries.id
   WHERE users.id = \''.pg_escape_string($login_id).'\' ORDER BY beer ASC');
while ($row = pg_fetch_row($result)){
   echo('<tr><td>'.$row[1].'</td><td>'.$row[3].'</td><td>'.$row[2].'</td><td><a href="user_remove_beer.php?beer='.$row[0].'">Poista</a></td></tr>'."\n");
}
?>
    </table>
    <a href="handle_logout.php">Kirjaudu ulos</a>
  </body>

</html>

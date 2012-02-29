<?php

require('handle_login.php');

// Lisätään olut, jos kaikki tiedot on kunnossa.

$success = false;
$error = '';
$message = '';

if ($_POST['add_beer']){   
   
  $beer_id = intval($_POST['beer_id']);

  // Lisätään olut tietokantaan, jos sitä ei ennestään ole

  if ($beer_id > 0){
    $result = pg_query($connection, 'SELECT * FROM beers WHERE id = '.$beer_id);
    if (!pg_fetch_row($result)){
      $error = 'Virheellinen beer_id.';
    }
  } else if (trim($_POST['new_beer_name']) != ''){
    $new_beer_brewery_id = intval($_POST['new_beer_brewery_id']);

    // Lisätään panimo, jos sitä ei ole

    if ($new_beer_brewery_id > 0){
      $result = pg_query($connection, 'SELECT * FROM breweries WHERE id = '.pg_escape_string($new_beer_brewery_id));
      if (!pg_fetch_row($result)){
        $error = 'Virheellinen beer_brewery_id';
      }
    } else if (trim($_POST['new_beer_brewery_name']) != '') {

      // Tarkistetaan aluksi, yritetäänkö lisätä jo olemassaolevalla nimellä olevaa panimoa.

      $row = pg_fetch_row(pg_query($connection, 'SELECT id FROM breweries WHERE name = \''.pg_escape_string(trim($_POST['new_beer_brewery_name'])).'\''));
      if ($row){
        $new_beer_brewery_id = $row[0];
      } else {
        pg_query($connection, 'INSERT INTO breweries (name) VALUES (\''.pg_escape_string(trim($_POST['new_beer_brewery_name'])).'\')');
        $row_brewery = pg_fetch_row(pg_query($connection, 'SELECT currval(\'breweries_id_seq\')'));
        $new_beer_brewery_id = $row_brewery[0];
      }
    } else {
      $new_beer_brewery_id = 'NULL';
    }

    $new_beer_style = intval($_POST['new_beer_style']);
    if ($new_beer_style == 0)
      $new_beer_style = 'NULL';

    pg_query($connection, 'INSERT INTO beers (name, brewery, style) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', '.pg_escape_string($new_beer_brewery_id).', '.$new_beer_style.')');
    $result = pg_query($connection, 'SELECT currval(\'beers_id_seq\')');
    $row = pg_fetch_row($result);
    $beer_id = $row[0];
  } else {
    $error = 'Et antanut oluen nimeä.';
  }
  
  // Lisätään muistiinpano.

  if (!$error){
    pg_query($connection, 'INSERT INTO tastings (t_beer, t_user, comment) VALUES ('.pg_escape_string($beer_id).', '.pg_escape_string($login_id).', \''.pg_escape_string($_POST['comment']).'\')');
    $message = 'Olut lis&auml;ttiin onnistuneesti.';
  }
}

?>
<html>

<?php include('static_elements/head.php'); ?>

  <body>
    <h1><?php echo($login_username); ?></h1>

<?php
if ($login_admin)
   echo('<a href="admin_home.php">P&auml;iv&auml;kirjan hallinta</a><br>');

if ($error)
   echo('<div class="error">'.$error.'</div>');
if ($message)
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
	<tr>
	  <td>Oluttyyli:</td>
	  <td>
	    <select name="new_beer_style">
	      <option value="0">-</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM styles');
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

    <h2>Oluesi<?php
$result = pg_query($connection, 'SELECT COUNT(*) FROM tastings LEFT JOIN beers ON tastings.t_beer = beers.id WHERE tastings.t_user = \''.pg_escape_string($login_id).'\'');
$row = pg_fetch_array($result);
echo(' (Oluita: '.$row[0].', Panimoita: ');
$result = pg_query($connection, 'SELECT COUNT(DISTINCT breweries) FROM breweries INNER JOIN beers ON beers.brewery = breweries.id INNER JOIN tastings ON beers.id = tastings.t_beer WHERE tastings.t_user = \''.pg_escape_string($login_id).'\'');
$row = pg_fetch_array($result);

echo($row[0].')');
?></h2>
    <table border=1>
      <tr>
	<th><a href="user_home.php?order_by=beer"> Olut</a></th>
	<th><a href="user_home.php?order_by=brewery">Panimo</a></th>
	<th><a href="user_home.php?order_by=style">Oluttyyli</a></th>
	<th>Kommentteja</th>
	<th>Muokkaa kommenttia</th>
	<th>Poista olut</th>
      </tr>
<?php

$order_by = 'beers.name';
if ($_GET['order_by'] == 'brewery')
  $order_by = 'breweries.name';
if ($_GET['order_by'] == 'style')
  $order_by = 'styles.name';

$result = pg_query($connection,
   'SELECT beers.id, beers.name, tastings.comment, breweries.name, styles.name FROM users INNER JOIN tastings ON users.id = tastings.t_user
   INNER JOIN beers ON tastings.t_beer = beers.id
   LEFT JOIN breweries ON beers.brewery = breweries.id
   LEFT JOIN styles ON beers.style = styles.id
   WHERE users.id = \''.pg_escape_string($login_id).'\' ORDER BY '.$order_by.', beers.name ASC');
while ($row = pg_fetch_row($result)){
   echo('<tr><td>'.$row[1].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[2].'</td>
<td><a href="user_edit_comment.php?beer='.$row[0].'">Muokkaa</a></td>
<td><a href="user_remove_beer.php?beer='.$row[0].'">Poista</a></td></tr>'."\n");
}
?>
    </table>
    <a href="handle_logout.php">Kirjaudu ulos</a>
<br><br>
  </body>

</html>

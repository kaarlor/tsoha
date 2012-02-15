<?php

require('handle_login.php');

if (!$login_admin){
  header('Location: index.php');
  exit;
}

if ($_POST['action'] == 'add_beer' && trim($_POST['new_beer_name']) != ''){
  $query_brewery = 'NULL';
  if (pg_fetch_row(pg_query($connection, 'SELECT * FROM breweries WHERE id = \''.intval($_POST['new_beer_brewery_id']).'\'')))
    $query_brewery = '\''.intval($_POST['new_beer_brewery_id']).'\'';
  $query_style = 'NULL';
  if (pg_fetch_row(pg_query($connection, 'SELECT * FROM styles WHERE id = \''.intval($_POST['new_beer_style']).'\'')))
    $query_style = '\''.intval($_POST['new_beer_style']).'\'';

  pg_query('INSERT INTO beers (name, brewery, style) VALUES (\''.pg_escape_string($_POST['new_beer_name']).'\', '.$query_brewery.', '.$query_style.')');
}


if ($_POST['action'] == 'add_brewery' && trim($_POST['new_brewery_name']) != ''){
  pg_query('INSERT INTO breweries (name) VALUES (\''.pg_escape_string($_POST['new_brewery_name']).'\')');
}

?>
<html>

<?php include('static_elements/head.php'); ?>

  <body>
    <h1>Olutp&auml;iv&auml;kirjan hallinta</h1>

<?php
if ($error)
   echo('<div class="error">'.$error.'</div>');
if ($message)
     echo('<div class="message">'.$message.'</div>');
?>

    <h2>P&auml;iv&auml;kirjan oluet</h2>
    <form method="POST" action="admin_remove_beers.php">
      <input type="hidden" name="action" value="remove_beers">
      	<table border="1">
	  <tr><th>Panimo</th><th>Olut</th><th>Olutlaji</th><th>Poista olut</th><th>Muokkaa</th><th>Maistaneita</th></tr>
<?php
  $result = pg_query($connection, 'SELECT beers.id, beers.name, breweries.name, styles.name
                                   FROM beers LEFT JOIN breweries ON beers.brewery = breweries.id
				   LEFT JOIN styles ON beers.style = styles.id
                                   ORDER BY breweries.name ASC');
  while ($row = pg_fetch_row($result)){
    echo('<tr><td>'.$row[2].'</td><td>'.$row[1].'</td><td>'.$row[3].'</td><td><input type="checkbox" name="remove_'.$row[0].'"></td><td><a href="admin_beer.php?beer_id='.$row[0].'">Muokkaa</a></td><td>');

    $beer_row = pg_fetch_row(pg_query($connection, 'SELECT count(*) FROM tastings WHERE t_beer = \''.$row[0].'\''));
    echo($beer_row[0]);

    echo('</td></tr>'."\n");
  }
?>
	 </table>
	 <input type="submit" value="Poista valitut oluet">
     </form>

     <form method="POST" action="admin_home.php">
     <input type="hidden" name="action" value="add_beer">
     <h3> Lis&auml;&auml; tietokantaan uusi olut </h3>
     <table>
       <tr>
         <td>Nimi:</td><td><input type="text" name="new_beer_name"></td>
       </tr>
	<tr>
	  <td>Panimo:</td>
	  <td>
	    <select name="new_beer_brewery_id">
	      <option value="0">-</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM breweries ORDER BY name ASC');
		 while ($row = pg_fetch_row($result)){
                   echo('	      <option value="'.$row[0].'">'.$row[1].'</option>'."\n");
		 }
		 ?>
	    </select>
	  </td>
	</tr>
		<tr><td>Olutlaji:</td><td>
			<select name="new_beer_style">
	      <option value="0">-</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM styles');
		 while ($row = pg_fetch_row($result)){
		   echo('	      <option value="'.$row[0].'"');
		   if ($row[0] == $beer_style)
		      echo(' selected="selected"');
		   echo('>'.$row[1].'</option>'."\n");
		 }
		 ?>
			</select>
       		</td></tr>

      </table>
      <input type="submit" value="Lis&auml;&auml; olut tietokantaan">
    </form>

<h2>P&auml;iv&auml;kirjan panimot</h2>
<table border="1">
  <tr><th>Panimo</th><th>Poista panimo</th></tr>
<?php
$result = pg_query($connection, 'SELECT id, name FROM breweries ORDER BY name ASC');
while ($row = pg_fetch_row($result)){

  $result2 = pg_query($connection, 'SELECT * FROM beers WHERE brewery = \''.pg_escape_string($row[0]).'\'');

  echo('<tr><td>'.$row[1].'</td><td>');
  if (!pg_fetch_row($result2))
    echo('<a href="admin_remove_brewery.php?brewery='.$row[0].'">Poista</a>');
  echo('</td></tr>'."\n");
}
?>
</table>
Voit poistaa vain sellaisen panimon, jolla ei ole oluita tietokannassa.

     <form method="POST" action="admin_home.php">
     <input type="hidden" name="action" value="add_brewery">
     <h3> Lis&auml;&auml; tietokantaan uusi panimo </h3>
     <table>
       <tr>
         <td>Nimi:</td><td><input type="text" name="new_brewery_name"></td>
       </tr>
      </table>
      <input type="submit" value="Lis&auml;&auml; panimo tietokantaan">
    </form>

    <a href="user_home.php">Siirry takaisin omalle sivullesi</a>
  </body>

</html>

<?php

require('handle_login.php');

if (!$login_admin){
  header('Location: index.php');
  exit;
}

$beer_id = intval($_GET['beer_id']);

if ($_POST['modify_beer']){
  $q_brewery_id = intval($_POST['beer_brewery']);
  if ($q_brewery_id == '0')
    $query_brewery = 'NULL';
  else
    $query_brewery = '\''.$q_brewery_id.'\'';

  $q_style_id = intval($_POST['beer_style']);
  if ($q_style_id == '0')
    $query_style = 'NULL';
  else
    $query_style = '\''.$q_style_id.'\'';

  pg_query($connection, 'UPDATE beers SET name = \''.pg_escape_string(trim($_POST['beer_name'])).'\', brewery = '.$query_brewery.',
                         style = '.$query_style.' WHERE id = \''.pg_escape_string($beer_id).'\'');
}

$row = pg_fetch_row(pg_query($connection, 'SELECT id, name, brewery, style FROM beers WHERE id = \''.pg_escape_string($beer_id).'\''));

if (!$row){
  header('Location: admin_home.php');
  exit;
}

$beer_name = $row[1];
$beer_brewery = $row[2];
$beer_style = $row[3];

?>
<html>

<?php include('static_elements/head.php'); ?>

  <body>
    <h1>Muokkaa olutta &quot;<?php echo($beer_name); ?>&quot;</h1>

<?php
if ($error)
   echo('<div class="error">'.$error.'</div>');
if ($message)
     echo('<div class="message">'.$message.'</div>');
?>

    <form method="POST" action="admin_beer.php?beer_id=<?php echo($beer_id); ?>">
    <input type="hidden" name="modify_beer" value="true">
      	<table>
		<tr><td>Nimi:</td><td><input type="text" name="beer_name" value="<?php echo($beer_name); ?>"></td></tr>
		<tr><td>Panimo:</td><td>
			<select name="beer_brewery">
	      <option value="0">-</option>
	      <?php
		 $result = pg_query($connection, 'SELECT id, name FROM breweries ORDER BY name ASC');
		 while ($row = pg_fetch_row($result)){
		   echo('	      <option value="'.$row[0].'"');
		   if ($row[0] == $beer_brewery)
		      echo(' selected="selected"');
		   echo('>'.$row[1].'</option>'."\n");
		 }
		 ?>
			</select>
       		</td></tr>
		<tr><td>Olutlaji:</td><td>
			<select name="beer_style">
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
        <input type="submit" value="Tee muutokset">
     </form>
    <a href="admin_home.php">Siirry takaisin hallintosivulle</a>
  </body>

</html>

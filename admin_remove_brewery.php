<?php

require('handle_login.php');

if (!$login_admin){
  header('Location: user_home.php');
  exit;
}

$brewery = intval($_GET['brewery']);
$row = pg_fetch_row(pg_query($connection, 'SELECT id, name FROM breweries WHERE id = \''.pg_escape_string($brewery).'\''));
if (!$row){
  header('Location: admin_home.php');
  exit;
}

$brewery_name = $row[1];

// varmistetaan, ettei panimolla ole oluita.
$result = pg_query($connection, 'SELECT * FROM beers WHERE brewery = \''.$brewery.'\'');
if (pg_fetch_row($result)){
  header('Location: admin_home.php');
  exit;
}

if ($_GET['confirm']){
  pg_query($connection, 'DELETE FROM breweries WHERE id = \''.pg_escape_string($brewery).'\'');
  header('Location: admin_home.php');
  exit;
}

?>

<html>
<?php include('static_elements/head.php'); ?>
  <body>
<?php
echo('    Oletko varma, että haluat poistaa panimon &quot;'.$brewery_name.'&quot; p&auml;iv&auml;kirjasta?');

echo('<br><a href="admin_remove_brewery.php?brewery='.$brewery.'&confirm=true">Kyllä</a> <a href="admin_home.php">En</a>');
?>
  </body>
  
</html>

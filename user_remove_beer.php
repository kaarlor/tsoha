<?php
require('handle_login.php');

if (intval($_GET['beer']) == 0){
   header('Location: user_home.php');
   exit;
}

if ($_GET['confirm'] == true){
   pg_query($connection, 'DELETE FROM tastings WHERE t_user = \''.pg_escape_string($login_id).'\'
                          AND t_beer = \''.pg_escape_string(intval($_GET['beer'])).'\'');
   header('Location: user_home.php?beer_removed=true');
   exit;
}

$result = pg_query($connection, 'SELECT beers.name, breweries.name FROM beers LEFT JOIN breweries on beers.brewery = breweries.id
                                 WHERE beers.id = \''.pg_escape_string($_GET['beer']).'\'');
if ($result){
   $row = pg_fetch_row($result);
} else {
   header('Location: user_home.php');
   exit;
}
?>

<html>

<?php include('static_elements/head.php'); ?>

  <body>
    Oletko varma, että haluat poistaa oluen
<?php
  echo('&quot;'.$row[0].'&quot; ');
  if ($row[1] != '') echo('('.$row[1].') ');
?>
listaltasi?<br>
    <a href="user_remove_beer.php?beer=<?php echo $_GET['beer']?>&confirm=true">Kyllä</a> <a href="user_home.php">En</a>
  </body>

</html>
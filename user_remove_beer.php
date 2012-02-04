<?php
   require('db/connection.php');
   require('handle_login.php');
   
if (!$login){
   header('Location: index.php');
   exit;
}

if (!$_GET['beer'] || intval($_GET['beer']) == 0){
   header('Location: user_home.php');
   exit;
}   
if ($_GET['confirm'] == true){
   pg_query($connection, 'DELETE FROM tastings WHERE t_user = \''.pg_escape_string($login_id).'\' AND t_beer = \''.pg_escape_string(intval($_GET['beer'])).'\'');
   header('Location: user_home.php?beer_removed=true');
   exit;
}

$result = pg_query($connection, 'SELECT name FROM beers WHERE id = \''.pg_escape_string($_GET['beer']).'\''); //..'\''
if ($result){
   $beer_name = pg_fetch_row($result);
} else {
   header('Location: user_home.php');
   exit;
}

?>

<html>
 
<?php include('static_elements/head.php'); ?>
  
  <body>
    Oletko varma, että haluat poistaa oluen &quot;<?php echo($beer_name[0]);?>&quot; listaltasi?<br>
    <a href="user_remove_beer.php?beer=<?php echo $_GET['beer']?>&confirm=true">Kyllä</a> <a href="user_home.php">En</a>
  </body>
  
</html>

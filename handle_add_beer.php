<?php
require('handle_login.php');

$success = false;

if ($_POST['new_beer']){
   $beer_id = -1;
   $beer_id = intval($_POST['beer_id']);
  if ($beer_id > 0) {
    $result = pg_query($connection, 'SELECT * FROM beers WHERE id = '.pg_escape_string($beer_id));
    if (pg_fetch_row($result)){
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.pg_escape_string($beer_id).', '.pg_escape_string($login_id).')');
      $success = true;
    } else {
      echo('Virhe: Virheellinen beer_id.');
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
        pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).')');
        $success = true;
      } else {
         echo('Virhe: Virheellinen new_beer_brewery_id.');
      }
    } else if ($new_beer_brewery_id == 0 && trim($_POST['new_beer_brewery_name']) != '') {
      pg_query($connection, 'INSERT INTO breweries (name) VALUES (\''.pg_escape_string(trim($_POST['new_beer_brewery_name'])).'\')');
      $row_brewery = pg_fetch_row(pg_query($connection, 'SELECT currval(\'breweries_id_seq\')'));
      pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.pg_escape_string(trim($_POST['new_beer_name'])).'\', '.pg_escape_string($row_brewery[0]).')');
      $row = pg_fetch_row(pg_query($connection, 'SELECT currval(\'beers_id_seq\')'));
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.pg_escape_string($row[0]).', '.pg_escape_string($login_id).')');
      $success = true;
    }
  }
}

if ($success){
   header('Location: user_home.php');
   exit;
}
?>
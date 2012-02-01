<?php
require('handle_login.php');

$success = false;

if ($_POST['new_beer']){
   $beer_id = -1;
   $beer_id = intval($_POST['beer_id']);
  if ($beer_id > 0) {
    $result = pg_query($connection, 'SELECT * FROM beers WHERE id = '.addslashes($beer_id));
    if (pg_fetch_row($result)){
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.addslashes($beer_id).', '.addslashes($login_id).')');
      $success = true;
    } else {
      echo('Virhe: Virheellinen beer_id.');
    }
  } else if ($beer_id == 0 && trim($_POST['new_beer_name']) != '') {
    $new_beer_brewery_id = -1;
    $new_beer_brewery_id = intval($_POST['new_beer_brewery_id']);
    if ($new_beer_brewery_id > 0){
      $result = pg_query($connection, 'SELECT * FROM breweries WHERE id = '.addslashes($new_beer_brewery_id));
      if (pg_fetch_row($result)){
        pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.addslashes(trim($_POST['new_beer_name'])).'\', '.addslashes($new_beer_brewery_id).')');
        $result = pg_query($connection, 'SELECT currval(\'beers_id_seq\')');
        $row = pg_fetch_row($result);
        pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.addslashes($row[0]).', '.addslashes($login_id).')');
        $success = true;
      } else {
         echo('Virhe: Virheellinen new_beer_brewery_id.');
      }
    } else if ($new_beer_brewery_id == 0 && trim($_POST['new_beer_brewery_name']) != '') {
      pg_query($connection, 'INSERT INTO breweries (name) VALUES (\''.addslashes(trim($_POST['new_beer_brewery_name'])).'\')');
      $row_brewery = pg_fetch_row(pg_query($connection, 'SELECT currval(\'breweries_id_seq\')'));
      pg_query($connection, 'INSERT INTO beers (name, brewery) VALUES (\''.addslashes(trim($_POST['new_beer_name'])).'\', '.addslashes($row_brewery[0]).')');
      $row = pg_fetch_row(pg_query($connection, 'SELECT currval(\'beers_id_seq\')'));
      pg_query($connection, 'INSERT INTO tastings (t_beer, t_user) VALUES ('.addslashes($row[0]).', '.addslashes($login_id).')');
      $success = true;
    }
  }
}

if ($success){
   header('Location: user_home.php');
   exit;
}
?>
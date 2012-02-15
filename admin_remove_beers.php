<?php

require('handle_login.php');

if (!$login_admin){
  header('Location: user_home.php');
  exit;
}

if (!$_GET['confirm']){
  // Selvitetään aluksi, mitkä oluet ovat poistouhan alla, ja laitetaan ne muuttujaan $to_remove[].

  $number_of_beers = 0;
  $row = pg_fetch_row(pg_query($connection, 'SELECT last_value FROM beers_id_seq'));
  for ($i = 1; $i <= $row[0]; $i++){
    if ($_POST['remove_'.$i]){
      $to_remove[$number_of_beers] = $i;
      $number_of_beers++;
    }
  }
  if ($number_of_beers == 0){
    header('Location: admin_home.php');
    exit;
  }
} else {
  $to_remove_array = explode(',',$_GET['to_remove']);
  foreach ($to_remove_array as $i => $id){
    pg_query($connection, 'DELETE FROM tastings WHERE t_beer = \''.pg_escape_string($id).'\'');
    pg_query($connection, 'DELETE FROM beers WHERE id = \''.pg_escape_string($id).'\'');
  }
  header('Location: admin_home.php');
  exit;
}

?>

<html>
<?php include('static_elements/head.php'); ?>
  <body>
    Oletko varma, että haluat poistaa seuraavat oluet p&auml;iv&auml;kirjasta? Huomaa, ett&auml; ihmiset, jotka ovat maistaneet n&auml;it&auml; oluita, eiv&auml;t v&auml;ltt&auml;m&auml;tt&auml; pid&auml; t&auml;st&auml;.<br><br>
<?php

foreach ($to_remove as $i => $id){
  $result = pg_query($connection, 'SELECT beers.id, beers.name, breweries.name FROM beers LEFT JOIN breweries ON beers.brewery = breweries.id
                                   WHERE beers.id = \''.pg_escape_string($id).'\'');
  if ($row = pg_fetch_row($result)){
    echo($row[1]);
    if ($row[2])
      echo(' ('.$row[2].')');
    echo('<br>'."\n");
  }
}
echo('<br><a href="admin_remove_beers.php?to_remove='.implode($to_remove,',').'&confirm=true">Kyllä</a> <a href="admin_home.php">En</a>');
?>
  </body>
  
</html>

<?php

require('handle_login.php');

if (!$_GET['beer']){
   header('Location: user_home.php');
   exit;
} else {
   $beer_id = intval($_GET['beer']);

   if ($_POST['edit_comment']){
      pg_query($connection, 'UPDATE tastings SET comment = \''.pg_escape_string(trim($_POST['comment'])).'\'
      			     WHERE t_user = \''.pg_escape_string($login_id).'\' AND t_beer = \''.pg_escape_string($beer_id).'\'');
      $message = 'Muutokset suoritettiin onnistuneesti.';
   }

   $beer_array = pg_fetch_array(pg_query('SELECT beers.name, tastings.comment FROM tastings LEFT JOIN beers ON tastings.t_beer = beers.id
   	       	 		          LEFT JOIN users ON tastings.t_user = users.id
                                          WHERE users.id = \''.$login_id.'\' AND beers.id = \''.$beer_id.'\''));
   if (!$beer_array){
      header('Location: user_home.php');
      exit;
   }
}
?>

<html>

<?php include('static_elements/head.php'); ?>

<body>
	<h2>Kommenttisi oluesta <?php echo($beer_array['name']); ?></h2><br>

<?php
if ($error)
   echo('<div class="error">'.$error.'</div>');
if ($message)
     echo('<div class="message">'.$message.'</div>');
?>

	<form method="POST" action="user_edit_comment.php?beer=<?php echo($beer_id); ?>">
	      <input type="hidden" name="edit_comment" value="true">
	      <textarea rows="10" cols="30" name="comment"><?php echo($beer_array['comment']);?></textarea><br>
	      <input type="submit" value="Muokkaa kommenttiasi">
	</form>
	<a href="user_home.php">Palaa olutlistaasi</a>
</body>

</html>
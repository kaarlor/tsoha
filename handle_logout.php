<?php
setcookie('username','',time());
setcookie('password','',time());
header('Location: index.php');
exit;
?>
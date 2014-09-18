<?php
	session_start();
	session_destroy();
	setcookie('username', '', time()-86400);

	header("location: index.php");
?>
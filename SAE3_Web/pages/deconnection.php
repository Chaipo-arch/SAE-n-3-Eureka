<?php 
	session_start();
	session_destroy(); // Destruction de la session
	header('Location: ../index.php'); // renvoi vers la page d'authentification.
	exit();
?>

<?php
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	
	// Intégration des fonctions qui seront utilisées pour les acces à la BD
	require('../fonctions/gestionBD.php');
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoie vers l'index
		header('Location: ../index.php');
		exit();
	} 


?>


<p>slaut</p>
<?php
session_start();

	
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php?action=renvoi');
     exit();
}
require('../fonctions/gestionBD.php');
require("../services/planningService.php");

	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoie vers l'index
		header('Location: ../index.php');
		exit();
	} 

    var_dump($_GET);
    var_dump($_POST);
    $var = getPlanningEntreprise(getPDO(),$_GET["entreprise"]);
    var_dump($var);
?>


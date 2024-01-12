<?php
session_start();
include("../services/AdminService.php");
include("../services/ForumService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php?action=renvoi');
  exit();
}

if ($_SESSION['role'] != "Admin") {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: forum.php');
    exit();
}
require('../fonctions/gestionBD.php');
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoie vers l'index
		header('Location: ../index.php');
		exit();
	} 
$pdo = getPDO();
if(isset($_POST['edition'])) {
    ModifierForumCaracteristiques($pdo,htmlspecialchars($_POST['date']),htmlspecialchars($_POST['dateLimite']),htmlspecialchars($_POST['duree']),
    htmlspecialchars($_POST['debut']),htmlspecialchars($_POST['fin']));
}
$caracteristiques = getForumCaracteristiques($pdo);
$date = $caracteristiques['date'];
$duree = $caracteristiques['duree'];
$dateLimite = $caracteristiques['dateLimite'];
$debut = $caracteristiques['Heure_debut'];
$fin = $caracteristiques['Heure_fin'];

?>
<html>
    <head>
        <title>Eureka - Forum (ADMIN)</title>
        <meta charset ="utf-8"/>
        <link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
        <link href="../css/EntrepriseCss.css" rel="stylesheet">
        <link href="../css/HeaderCss.css" rel="stylesheet">
        <link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
    </head>	
    <body>
        <?php 
        include("../fonctions/viewHelper.php");
       headerHelper();
        ?>
        <div class="container separation">
            <form action="forumA.php" method="post">
                <div class="row">
                    <div class ="col-md-12 centre">
                        <h2>Forum (ADMIN)</h2></br>
                    </div>
                    <div class ="col-md-4 centre">
                        <input type="hidden" name="controller" value="Admin">
                        <input type="hidden" name="action" value="modifierForum">
                        <input type="hidden" name="edition" value="true">
                        Date</br>
                        <input type="text" name="date" value="<?php if(isset($date)) { echo $date;}?>">
                    
                    </div>
                    <div class ="col-md-4 centre">
                        Durée par défaut (minutes)</br>
                        <input type="text" name="duree" value="<?php if(isset($duree)) { echo $duree;}?>">
                    </div>
                    <div class ="col-md-4 centre">
                        Date Limite (rendez Vous)</br>
                        <input type="text" name="dateLimite" value="<?php if(isset($dateLimite)) { echo $dateLimite;}?>">
                    </div>
                    </br>
                    <div class ="col-md-6 centre">
                        Heure Debut </br>
                        <input type="text" name="debut" value="<?php if(isset($debut)) { echo $debut;}?>">
                    </div>
                    </br>
                    <div class ="col-md-6 centre">
                        Heure Fin </br>
                        <input type="text" name="fin" value="<?php if(isset($fin)) { echo $fin;}?>">
                    </div>
                    </br>
                    <div class ="col-md-12 centre">
                        </br>
                    <input type="submit"  value="Modifier Caractéristiques forum">
                    </div>
                
                </div>
            </form>
            <div class ="col-md-12 centre">
                <form action="GestionFiliere.php" method="post">

                </form>
            </div>
            <a  href="forum.php">Retour</a>
        </div>
        <?php footerHelper(); ?>
        
    </body>
</html>
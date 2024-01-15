<?php
session_start();
include("../services/AdminService.php");
include("../services/ForumService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté
  header('Location: ../index.php?action=renvoi');
  exit();
}

if ($_SESSION['role'] != "Admin") {
    //On est déja connecté
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
if(isset($_POST['action'])&& $_POST['action'] == "supprimerDonnees") {
    suppressionDonnees($pdo);
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
        <link href="../css/user.css" rel="stylesheet">
    </head>	
    <body>
        <?php 
        include("../fonctions/viewHelper.php");
       headerHelper();
        ?>
        <p class="marge-header">  .</p>
        <div class="container separation div-box-Forum ">
            <form action="forumA.php" method="post">
                <div class="row">
                    <div class ="col-12 centre">
                        <h2>Modification Information Forum</h2></br>
                    </div>
                    <div class ="col-12 centre">
                        <input type="hidden" name="controller" value="Admin">
                        <input type="hidden" name="action" value="modifierForum">
                        <input type="hidden" name="edition" value="true">
                        Date</br>
                        <input class="rounded" type="date" name="date" value="<?php if(isset($date)) { echo $date;}?>">
                    
                    </div>
                    <div class ="col-12 centre">
                        Durée par défaut (minutes)</br>
                        <input class="rounded" type="time" name="duree" value="<?php if(isset($duree)) { echo $duree;}?>">
                    </div>
                    <div class ="col-12 centre"> 
                        Date Limite (rendez Vous)</br>
                        <input class="rounded" type="date" name="dateLimite"  value="<?php if(isset($dateLimite)) { echo $dateLimite;}?>">
                    </div>
                    </br>
                    <div class ="col-12 centre">
                        Heure Debut </br>
                        <input class="rounded" type="time" name="debut" value="<?php if(isset($debut)) { echo $debut;}?>">
                    </div>
                    </br>
                    <div class ="col-12 centre">
                        Heure Fin </br>
                        <input class="rounded" type="time" name="fin"  value="<?php if(isset($fin)) { echo $fin;}?>">
                    </div>
                    </br>
                    <div class ="col-12 centre">
                    </br>
                    <input class="btn btn-form-control mr-sm-1 btn-primary" type="submit" value="Enregistrer Modification">
                    </div>
                
                </div>
            </form>
            <div class ="col-md-12 centre">
                <form action="GestionFiliere.php" method="post">
                    <input class="btn btn-form-control mr-sm-1 btn-primary" type="submit"  value="gérer filières">
                </form>
            </div>
            <div class ="col-md-12 centre">
                <input class="btn btn-form-control mr-sm-1 btn-danger" value="Reinitialiser Forum" id="reini">
                </br></br>
            </div>
            <a  href="forum.php"><button type="button" class="btn btn-primary">Retour</button></a>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                voulez vous vraimment reinitialiser le forum ?
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="annuler">Annuler</button>
                <form action="forumA.php" method="post">
                
                <button type="button submit" class="btn btn-danger" id="confirmerSuppressionBtn">Supprimer</button>
                <?php ?>
                    <input name="action" type="hidden" value="supprimerDonnees">
                    
                    </div>
                </form>
            </div>
        <script src="../js/reini.js"></script>
    </body>
</html>
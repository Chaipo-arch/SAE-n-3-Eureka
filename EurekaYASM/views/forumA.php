<?php
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php?action=renvoi');
  exit();
}
?>
<html>
<head>
	<title>Forum</title>
	<meta charset ="utf-8"/>
	<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
    <link href="css/EntrepriseCss.css" rel="stylesheet">
	<link href="css/HeaderCss.css" rel="stylesheet">
	<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("fonctions/viewHelper.php");
   headerHelper();
  ?>
  <div class="container separation">
    <form action="index.php" method="post">
        <div class="row">
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
            <div class ="col-md-12 centre">
                </br>
            <input type="submit"  value="Modifier Caractéristiques forum">
            </div>
        </div>
        
    </form>
    <a  href="index.php">Retour</a>
</div>
    <?php footerHelper(); ?>
	
</body>
</html>
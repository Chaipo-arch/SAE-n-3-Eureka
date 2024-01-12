<?php
session_start();
include("../services/UserService.php");
include("../services/ForumService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php');
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
if($_SESSION['role'] == 'Etudiant')  {
  getSouhait($pdo,$_SESSION['IdUser']);
}

?>
<html>
<head>
	<title>Eureka - Forum</title>
  <link rel="icon" href="../eurekaLogoi.ico" />
  <link rel="icon" href="../eurekaLogo.jpg" />
	<meta charset ="utf-8"/>
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="../css/HeaderCss.css" rel="stylesheet">
  <link href="../css/EntrepriseCss.css" rel="stylesheet">
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("../fonctions/viewHelper.php");
   headerHelper();
  ?>
  
   <div class="container separation">
      <div class="col-md-12">
        
        <?php 
        if(isset($pageNTrouve)) {
          ?>
          La page que vous avez demandée n'a pas était trouvé 
        <?php } else {
          $caracteristiques = getForumCaracteristiques($pdo);?>
          <div class="row">
          <div class ="col-md-12 centre">
          <h1> Bienvenue sur le site Eureka <?php echo $_SESSION['role'] ; ?></h1>
         </div>
            <div class ="col-md-4 centre">
                
                Date</br>
                <?php if(isset($caracteristiques['date']) && $caracteristiques['date'] != "") { echo $caracteristiques['date'] ;} else { echo "Date non définie" ;} ?> 
                
            
            </div>
            <div class ="col-md-4 centre">
                Durée par défaut (minutes)</br>
                <?php if(isset($caracteristiques['duree']) && $caracteristiques['duree'] != "") { echo $caracteristiques['duree'] ;} else { echo "Durée non définie"; } ?> 
                
            </div>
            <div class ="col-md-4 centre">
                Date Limite (rendez Vous)</br>
                <?php if(isset($caracteristiques['dateLimite']) && $caracteristiques['dateLimite'] != "") { echo $caracteristiques['dateLimite'] ;} else { echo "Date limite non définie"; }?> 
            </div>
            </br>
        </div>
          <?php }?>
        </div>
  </div>
    <?php footerHelper(); ?>
	
</body>
</html>
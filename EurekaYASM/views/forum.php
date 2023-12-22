<?php
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php?action=renvoi');
  exit();
}
?>
<html>
<head>
	<title>Eureka - Forum</title>
	<meta charset ="utf-8"/>
	<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="css/HeaderCss.css" rel="stylesheet">
  <link href="css/EntrepriseCss.css" rel="stylesheet">
	<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("fonctions/viewHelper.php");
   headerHelper();
  ?>
  
   <div class="container separation">
      <div class="col-md-12">
        <?php 
        if(isset($pageNTrouve)) {
          ?>
          La page que vous avez demandée n'a pas était trouvé 
        <?php } else {?>
          <div class="row">
            <div class ="col-md-4 centre">
                
                Date</br>
                <?php echo $date ; ?> 
                
            
            </div>
            <div class ="col-md-4 centre">
                Durée par défaut (minutes)</br>
                <?php echo $duree ; ?> 
                
            </div>
            <div class ="col-md-4 centre">
                Date Limite (rendez Vous)</br>
                <?php echo $dateLimite ; ?> 
            </div>
            </br>
        </div>
          <?php }?>
        </div>
  </div>
    <?php footerHelper(); ?>
	
</body>
</html>
<?php
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php?action=renvoi');
    exit();
}
?>
<html>
<head>
	<title>Forum</title>
	<meta charset ="utf-8"/>
	<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="css/ForumCss.css" rel="stylesheet"/>
	<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("fonctions/viewHelper.php");
    headerHelper();
  ?>
  
    <?php footerHelper(); ?>
	
</body>
</html>
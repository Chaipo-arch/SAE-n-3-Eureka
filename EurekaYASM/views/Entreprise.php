
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>IUT BANK - Liste des comptes</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="css/monStyle.css" rel="stylesheet">
		<link href="css/ForumCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
	<?php 
    include("fonctions/viewHelper.php");
    headerHelper();
    ?>
	
	<form action="index.php">
	<input name="controller" type="hidden" value="Entreprise">
		<select name="filiere">
			<!-- option-->
		</select>
	</form>
	<form class="form-inline my-1 my-lg-1" action="index.php" method="get">
		<input name="action" type="hidden" value="recherche">
		<input name="controller" type="hidden" value="Entreprise">
		<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
	</form>	
    <?php footerHelper(); ?>
  </body>
</html>
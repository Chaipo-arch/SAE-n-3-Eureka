<?php

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php?action=renvoi');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Entreprises</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="css/monStyle.css" rel="stylesheet">
		
		<link href="css/EntrepriseCss.css" rel="stylesheet">
		<link href="css/HeaderCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
	<?php 
	include("fonctions/viewHelper.php");
	headerHelper();
    ?>
	</br>
	
	<div class="container separation">
		<div class="col-md-12">
			<div class= "row centre">
				<div class="col-md-2 centre">
					
					<form action="index.php" method="Post">
						<input name="controller" type="hidden" value="Entreprise">
						<input name="action" type="hidden" value="filiereChanger">
						<select name="filiere" type="submit">
							<option>Toutes</option>
							<!-- option-->
							<option>test1aaaaaaaaaaaaaaa</option>
							<option>test2</option>
						</select>
						<input type="submit" value="valider">
					</form>
				</div>
				<div class="col-md-10 centre">
					<form class="form my-1 my-lg-1" action="index.php" method="get">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
						<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
					</form>	
				</div>
			</div>
		</div>
		<?php 
		if(isset($_POST['entreprises'])) {
			foreach($_POST['entreprises'] as $entreprise) {?>
		<?php// for ($i = 0 ; $i< 10 ; $i= $i +1) { ?>
			<p>
			<div class="col-md-12 entrepriseCase">
				<div class= "row">
					<div class="col-md-2 case">
						logo
					</div>
					<div class="col-md-4 case">
						designation + secteur
					</div>
					<div class="col-md-6 case">
						voir plus de détails
					</div>
				</div>
			</div>
			</p>
		<?php } }?>
	</div>
	<?php var_dump($_SESSION);var_dump($_POST); ?>
    <?php footerHelper();
	 ?>
	 
  </body>
</html>
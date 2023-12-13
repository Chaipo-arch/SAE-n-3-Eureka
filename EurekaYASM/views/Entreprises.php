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
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>IUT BANK - Liste des comptes</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">
		<link href="../css/ForumCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
	<header>
		<div class="container-fluid">
			<nav class="navbar navbar-light bg-light ">
				<div class="col-md-12">
				
				
					<div class= "row">
						<div class= "col-md-2 d-flex justify-content-center">
							<img href="" alt="logo eureka">
						</div>
						<div class= "col-md-2 d-flex justify-content-center">
						<form class="form-inline my-1 my-lg-1" action="Recherche.php" method="get">
							<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
						</form>
						</div>
						<div class= "col-md-2 d-flex justify-content-center">
							<a class="nav-link active onglet" href="">Entreprises</a>
						</div>
						<div class= "col-md-2 d-flex justify-content-center">
							<a class="nav-link active onglet" href="">Mes souhaits</a>
						</div>
						<div class= "col-md-2 d-flex justify-content-center">
						</div>
						<div class= "col-md-2 d-flex justify-content-center">
							<a class="nav-link active onglet" href="deconnection.php">Déconnexion</a>
						</div>
					</div>
					
					
				

				
				</div>
			</nav>
		</div>
	</header>
	<form>
		<select name="filiere">
			<!-- option-->
		</select>
	</form>

	<footer>
		<div class="container-fluid">
			<div class="col-md-12">
				a
				<div class= "row">
				</div>
			</div>
		</div>
	</footer>
  </body>
</html>

<?php
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	if($_SESSION['IdUser']==1){
		echo "toto";
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

		<!-- Lien vers CSS fontawesome -->
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
		
	</head>

	<body>
  
	<h2>DASHBOARD</h2>
  
	<div class="col-4">
		<form action="deconnection.php" method="post">
			<br/><button class="btn btn-info btn-block" type="submit">Me déconnecter <i class='fa-regular fa-circle-xmark'></i></button>
		</form>
	</div>
	
	<?php 
		deconnecteBD(); // Appel de la fonction permettant de se deconnecter proprement.
	?>

<div class="col-md-4" ><canvas id="myChart"></canvas></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../JS/Dashboard.js"></script>


  </body>
  
</html>

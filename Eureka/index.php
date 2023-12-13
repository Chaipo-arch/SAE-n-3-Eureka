



<?php

	//TODO Faires ens orte de sauvegarder les données rentrer meme si erreurs
	//Quel duree ou l'on reste connecter ?
	//

	//connecteBD()

	session_start();
	$connecte=false; 			// Variable qui permettra de savoir si on a pu se connecter
	$tentativeConnection=false; // Variable qui permettra de savoir que l'on a tenté de se connecter
	$problemeDonnees=false;		// Variable qui se mettra à true si pbme d'accès à la BD
	$messageRetour="";			// Message à afficher si problème de donnéees
	$identifiantSaisi = ""; // Variable pour stocker l'identifiant saisi

	
	//TODO a modifier
	if (isset($_SESSION['connecte'])) {
		//On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
		header('Location: pages/page1.php');
		exit();
	}
	
	
	// Intégration des fonctions qui seront utilisées pour les acces à la BD
	require('fonctions/gestionBD.php');
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		$problemeDonnees=true;
		$messageRetour="Erreur d'authentification serveur. Merci de re-essayer plus tard.";
	} 

	if (isset($_POST['identifiant']) and isset($_POST['pwd'])) {
		// Les identifiants ont été saisis. Vérification si OK
		$tentativeConnection=true; // On stocke qu'un utilisateur veut se connecter pour afficher un message en cas de login ou pwd erroné

		$identifiantSaisi = $_POST['identifiant']; // Stocker l'identifiant saisi, qu'il soit valide ou non

		if (verifUtilisateur($_POST['identifiant'],$_POST['pwd'])) {
			// L'utilisateur a bien été trouvé dans la BD, 
			// les variables de sessions necessaires ont été positionnées dans la fonction
			$connecte=true;
		} 				
	}

	if ($connecte) {
		//On est connecté, on renvoie vers la page des comptes. 
		header('Location: pages/page1.php');
		exit();
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Connexion Eureka</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="css/monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

  <body>

	<div class="login-box">
		<h2>Login</h2>
		<?php 
			if ($problemeDonnees) {
		?>
		<div class="row">
			<div class="col-12">
				<?php echo $messageRetour; ?>
			</div>
		</div>
		<?php 
			} else {
		?>
		<form action="index.php" method="post">
			<?php 
				if ($tentativeConnection) { ?>
				<!-- On a essayé de se connecter mais cela a échoué, affichage d'un message d'erreur -->
					<div class="row">
						<div class="col-12">
							<p class="enRouge">Identifiant ou mot de passe incorrect</p>
						</div>
					</div>
			<?php
				}
			?>
			
			<div class="user-box">
				<input type="text" name="identifiant" required="" autocomplete="off" value="<?php echo htmlspecialchars($identifiantSaisi); ?>">
				<label>Username</label>
			</div>

			<div class="user-box">
				<input type="password" name="pwd" required="">
				<label>Password</label>
			</div>
			
			<div class="col-12">
				<input class="btn btn-info" type="submit" value="Me connecter">
			</div>
		</form>

		
		<?php
			} 
		?>	
	</div>

	<?php 
		deconnecteBD(); // Appel de la fonction permettant de se deconnecter proprement.
	?>
  </body>
</html>
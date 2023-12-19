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


	$rechercheUtilisateur = "";
	

	if (isset($_GET['chaineRecherche'])) {
		$rechercheUtilisateur = $_GET['chaineRecherche'];
	}
?>


<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Connexion Eureka</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

    <!-- Navbar -->
	<body>
    <nav class="nav bg-light justify-content-center">
        <a href="#" class="nav-link text-dark">Accueil</a>     
        <a href="#" class="nav-link text-dark">Consulter Entreprise</a>
		<a href="#" class="nav-link text-dark">Mon Planning</a>    
        <a href="#" class="nav-link text-dark">Contact</a>
		<a href="ajoutEntreprise.php" class="nav-link text-dark">test</a>     
    </nav>

    <!-- Home -->

    <section class="bg-dark d-flex flex-column justify-content-center align-items-center container custom-size">

        <p class="display-1 text-white text-center">Eureka</p>
        <!-- <p class="lead text-center text-white">Bienvenue cher ""ROLE"" sur le site de EUREKA, 
			EUREKA est un forum permettant de mettre en relation diverse entreprise avec des etudiants
		</p> -->

    </section>

    <!-- Grille Responsive -->

    <div class="container py-5 bg-light">

        <!-- <h2 class="display-4 text-center mb-5">Eureka</h2> -->
		<!-- 576 XS - > 576px S > 768px M > 992px L > 1200px Extra Large-->

        <div class="row">


			<form action="Forum.php" method="get">
				<?php
				// Requête pour récupérer les filières
				$sql = "SELECT DISTINCT field FROM filiere";
				$result = $connexion->query($sql);

				// Vérification des résultats et création de la liste déroulante
				
					echo '<select name="filiere">';
					echo '<option value="Toutes">Toutes</option>';

					while ($row = $result->fetch()) {
						?>
						<option value="<?php echo $row['field'];?>" <?php if(isset($_GET['filiere']) && $_GET['filiere']== $row['field']) { echo "selected";} ?> ><?php echo $row['field'];?> </option>
						<?php
					}

					
					echo '</select>';

					
				?>

				<input type="text" name="chaineRecherche" placeholder="Rechercher..." value="<?php echo htmlspecialchars($rechercheUtilisateur); ?>">
				<input type="submit" value="Rechercher">
			</form>

			<?php 


				
				if(isset($_GET['filiere'])){
					$filiere = $_GET['filiere'];
				} else {
					$filiere = 'Toutes';
				}
				if(isset($_GET['chaineRecherche'])){
					$recherche = $_GET['chaineRecherche'];
				} else {
					$recherche = '';	
				}
				$tableauEntreprise = RechercheEntreprise($connexion,$recherche,$filiere);
				//var_dump($tableauEntreprise);
				foreach($tableauEntreprise as $ligne) {


					echo '<div class="col-12">';
					echo '<div class="card mb-4 shadow-sm d-flex flex-row">';
					echo '<img src="'.$ligne['logo'].'">';
					echo '<div class="card-body d-flex flex-column justify-content-between">';
					echo '<p class="card-text">';
					echo $ligne['Designation'];
					echo '</p>';
					echo $ligne['presentation'];
					echo '<div class="btn-group mt-2">';
					echo '<button type="button" class="btn btn-sm btn-outline-secondary">
					Nous Découvrir
					</button>';
					echo '<button type="button" class="btn btn-sm btn-outline-secondary ml-1">
					Rendez vous
					</button>';
					echo '</div>
					</div>
					</div>
					</div>';
                } 
			?>
			

		</div>

                

		
	</body>

	<footer class="">
		<form action="deconnection.php" method="post">
			<br/><button class="btn btn-info btn-block" type="submit">Me déconnecter <i class='fa-regular fa-circle-xmark'></i></button>
		</form>
	</footer>
</html>

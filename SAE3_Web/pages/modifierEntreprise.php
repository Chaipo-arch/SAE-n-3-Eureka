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


	if (isset($_POST['id_entreprise'])) {
		$entreprise_id = $_POST['id_entreprise'];
		echo "L'entreprise choisi est : ";
		echo $entreprise_id;
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	


?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Modifier Entrerpise</title>
		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>

    <div id="container">
      <header>
        
      </header>
      
      <form class="formAjoutEntreprise" action="ajoutEntreprise.php" method="post">

        <div class="row caseCentrer">
          <!-- <div class=" col-md-4 col-sm-3">
            <img  src="../images/Eureka.png">
          </div> -->
          <div class=" col-md-8 col-sm-9 titreCentrer">
            <h2 class="h2center">Modifier Entreprise</h2>
          </div>
        </div>

			

			<details>
			<summary>Gérer les Intervenants</summary>
				<p>Contenu dépliable ici...</p>
			</details>

        
		  


			<details>
			<summary>Modifier Informations Entreprise</summary>
				<fieldset>
					<div>
						<label for="first-name">Nom de L'entreprise</label>
						<input type="text" name="nomEntreprise"  required >
						<br/>
						<label for="first-name">Secteur D'activité</label>
						<input type="text" name="secteurActivite" required>
						<br/>

						<label for="message">Presentation de l'entreprise</label>
						<br/>
						<textarea rows="5" cols="50" tabindex="-1" name="presentation" required></textarea>

						<p class="TextDescriptif">Localisation</p>
						<div class="row">
						
						<div class="col-md-5 col-sm-12 col-12">
							<input class="" name="ville" type="text" placeholder="Ville" required>
						</div>

						<div class="col-md-5 col-sm-8 col-8">
							<input class="" type="text" name="adresse" placeholder="Adresse" required>
						</div>

						<div class="col-md-2 col-sm-4 col-4">
							<input class="" type="text" name="cp" placeholder="Code Postal ex: 12000" pattern="[0-9]{5}" required>
						</div>
						
						</div>
						<!-- <label for="image">Importer une image :</label><br>
						<input type="file" name="image"><br><br>    -->
					</div>
					

					<div class="row caseCentrer">
						<div class=" col-md-4 col-sm-3 col-3">
						<a href="Forum.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
						</div>
						<div class=" col-md-8 col-sm-9 col-9 boutonDroite">
						<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Enregistrer">
						
						</div>
					</div>
				</fieldset>
			<!-- <input type="submit" name="submitButton" value="Ajouter l'Entreprise"> -->
			<!-- <input type="reset" value="Reset all info" tabindex="-1"> -->
			</details>

			<details>
			<summary>Supprimer Entreprise</summary>
				</br>
				<p>Cette Action est irréverssible</p>
				<button type="button" class="btn btn-primary btn-lg btn-block">Supprimer L'entreprise</button>
			</details>
			
      </form>
    </div>

	



    </body>
</html>
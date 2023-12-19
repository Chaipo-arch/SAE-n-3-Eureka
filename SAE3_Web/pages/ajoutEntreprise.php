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
		<title>Connexion Eureka</title>

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
        <div>
          <h1 class="h1center">AJOUTER UNE ENTREPRISE</h1>
          <img src="../images/Eureka.png">
        </div>
      </header>
      
      <form class="formAjoutEntreprise">
        <fieldset>
          <legend>Fields marked with * are required</legend>

          <!-- START DIV part-1 PERSONAL INFORMATION -->
          <div>
            <h2>Personal Information (required)</h2>
            <label for="first-name">Nom de L'entreprise</label>
            <input type="text" name="first-name" required>
            <br/>
            <label for="first-name">Secteur D'activité</label>
            <input type="text" name="first-name" required>
            <br/>

            <label for="message">Presentation de l'entreprise</label>
            <br/>
            <textarea rows="5" cols="50" id="message" tabindex="-1"></textarea>

            <p class="TextDescriptif">Localisation</p>
            <div class="row">
              
              <div class="col-5">
                <input class="" type="text" placeholder="Ville">
              </div>

              <div class="col-5">
                <input class="" type="text" placeholder="Adresse">
              </div>

              <div class="col-2">
                <input class="" type="text" placeholder="Code Postal">
              </div>
              
            </div>
            <label for="image">Importer une image :</label><br>
            <input type="file" name="image"><br><br>   
          </div>
          <!-- END DIV part-3 ACCOUNT INFORMATION -->
          <input type="submit" name="submitButton" value="Send-message">
          <input type="reset" value="Reset all info" tabindex="-1">
        </fieldset>
      </form>
    </div>
    </body>
</html>
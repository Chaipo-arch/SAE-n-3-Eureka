<?php
session_start();

	
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php?action=renvoi');
     exit();
}
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
		<title>Eureka - Votre planning</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">
		<link href="../css/HeaderCss.css" rel="stylesheet">
		<link href="../css/planning.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
		
	</head>
	
	<body>
    <?php 
    include("../fonctions/viewHelper.php");
    headerHelper();

    ?>
	<br/><br/><br/>
	<div class="container separation">
		<div class="col-md-12">
		<div class= "row centre">
					<div class="col-md-3 centre">
						<button href="visualisezLesPlanning.php">voir Planning déja généré</button>
				</div>
			</div>
		</div>
	</div>

	<br>
	
	
	<h1>Paramétrage du planning : </h1>
		<h2>voulez vous créer le planning d'une entreprise</h2>
		
		<form action="Gestionplanning.php" method="get">
		<label for="first-name"> entreprise pour lequel vous souhaitez créer un rendez-vous : </label>
		<select class="form-control" id="entreprise" name="entreprise">
		<?php $toutEntreprise = getEntreprises();
			foreach($toutEntreprise as $entreprise){
				echo '<option  value="'.$entreprise["id"].'">'.$entreprise["Designation"].'</option>';
			}?>
		</select>
		
		<br>
		<label for="first-name"> durée d'un rendez-vous (en heure) : </label>
		<input type="time" id="appt" name="appt" required />
		<br>
		<button onclick="ouvrirNouvellePage()" type="submit">générer le planning</button>

		</form>

<br>

		<h2>voulez vous exclure des entreprise de la génération du planning : </h2>
		<br>

		<form>

			<label for="first-name"> durée d'un rendez-vous (en heure) : </label>
			<input type="time" id="appt" name="appt" required />

			<input type="submit" >

		</form>
		<script>
    // Fonction pour ouvrir une nouvelle page
    function ouvrirNouvellePage() {
        // Spécifiez l'URL de la nouvelle page
		element = document.getElementById("entreprise");
		var nouvellePageURL = 'planningEntreprise1.php?entreprise=' + encodeURIComponent(element.value);
        // Ouvre la nouvelle page dans une nouvelle fenêtre ou un nouvel onglet
        window.open(nouvellePageURL, '_blank' , 'width=1500,height=1000');
    }
</script>
    <?php footerHelper(); ?>
  </body>
</html>
<?php
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	if ($_SESSION['role'] != "Admin") {
		//On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
		header('Location: forum.php');
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

	if (isset($_POST['action']) && $_POST['action'] == "ajoutIntervenant") {
		include("../services/AdminService.php");
		$nom= $_POST['nomIntervenant'];
		include("../services/FiliereService.php");
		ajoutIntervenant(null,$nom,$_POST['idEntreprise']);
		
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['action']) && $_POST['action'] == "modificationIntervenant") {
		include("../services/AdminService.php");
		$idIntervenant= $_POST['idIntervenant'];
		$nom= $_POST['nomIntervenant'];
		modifIntervenant(null,$nom,$idIntervenant);
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['action']) && $_POST['action'] == "supprimerIntervenant") {
		include("../services/AdminService.php");
		$idIntervenant= $_POST['idIntervenant'];
		SupprimerIntervenant(getPDO(),$idIntervenant);
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['action']) && $_POST['action'] == "modification") {
		include("../services/AdminService.php");
		$designation= $_POST['nomEntreprise'];
		$activite= $_POST['secteurActivite'];
		$logo= "a";
		$presentation= $_POST['presentation'];
		$ville= $_POST['ville'];
		$adresse= $_POST['adresse'];
		$cp= $_POST['cp'];
		
		$entreprise_id = $_POST['idEntreprise'];
		modifEntreprise(null,$designation,$activite,$logo,$presentation,$entreprise_id,$ville,$adresse,$cp);
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['idEntreprise'])) {
		$entreprise_id = $_POST['idEntreprise'];
		echo "L'entreprise choisi est : ";
		echo $entreprise_id;
		include("../services/EntrepriseService.php");
		//$intervenants = getIntervenants();
		$entreprise = getInfoEntreprise(getPdo(),$entreprise_id);

		$intervenants = getIntervenantEntreprise(null, $entreprise_id);
		
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	


?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Modifier Entreprise (ADMIN)</title>
		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="../monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>

    <div id="container" class="formAjoutEntreprise">
      <header>
        
      </header>
      
      

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
				<details>
					<summary>Modifier les Intervenants</summary>
					<?php 
					foreach($intervenants as $intervenant) { ?>
						<form action="modifierEntreprise.php" method="post">
							<fieldset>
							<div>
							
							<label for="first-name">Nom de L'intervenant</label>
							<input type="text" name="nomIntervenant" value="<?php echo $intervenant['name'] ;?>" required >
							
							<input name="action" type="hidden" value="modificationIntervenant">
							<input name="idIntervenant" type="hidden" value="<?php echo $intervenant[0]; ?>">
							<input name="idEntreprise" type="hidden" value="<?php echo $entreprise_id; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Enregistrer">
							
							<br/>
						</form>
						<form action="modifierFiliereDemande.php" method="post">
							<input name="idIntervenant" type="hidden" value="<?php echo $intervenant[0]; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne "  value="Voir filiere Demandé">
						</form>
						<form action="modifierEntreprise.php" method="post">
						<input name="action" type="hidden" value="supprimerIntervenant">
							<input name="idIntervenant" type="hidden" value="<?php echo $intervenant[0]; ?>">
							<input name="idEntreprise" type="hidden" value="<?php echo $entreprise_id; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne "  value="Supprimer <?php echo $intervenant['name'] ;?>">
						</form>
					<?php }
				?>
				</details>

				<details>
					<summary>Ajouter un Intervenant</summary>
						<form action="modifierEntreprise.php" method="post">
							<fieldset>
							<div>
							
							<label for="first-name">Nom de L'intervenant</label>
							<input type="text" name="nomIntervenant"  required >
							<br/>
							
							<input name="action" type="hidden" value="ajoutIntervenant">
							<input name="idEntreprise" type="hidden" value="<?php echo $entreprise_id; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Ajouter">
							
							<br/>
						</form>
				</details>
				
			</details>

        
		  


			<details>
			<summary>Modifier Informations Entreprise</summary>
				<form action="modifierEntreprise.php" method="post">
					<fieldset>
						<div>
							<label for="first-name">Nom de L'entreprise</label>
							<input type="text" name="nomEntreprise" value="<?php echo $entreprise['Designation'] ;?>" required >
							<br/>
							<label for="first-name">Secteur D'activité</label>
							<input type="text" name="secteurActivite" value="<?php echo $entreprise['activity_sector'] ;?>" required>
							<br/>

							<label for="message">Presentation de l'entreprise</label>
							<br/>
							<textarea rows="5" cols="50" tabindex="-1" name="presentation"  required><?php echo $entreprise['presentation'] ;?></textarea>

							<p class="TextDescriptif">Localisation</p>
							<div class="row">
							
							<div class="col-md-5 col-sm-12 col-12">
								<input class="" name="ville" type="text" placeholder="Ville" value="<?php if(isset($entreprise['ville'])) {echo $entreprise['ville'] ;} ?>" required>
							</div>

							<div class="col-md-5 col-sm-8 col-8">
								<input class="" type="text" name="adresse" placeholder="Adresse" value="<?php if(isset($entreprise['adresse'])) {echo $entreprise['adresse'] ; }?>" required>
							</div>

							<div class="col-md-2 col-sm-4 col-4">
								<input class="" type="text" name="cp" placeholder="Code Postal ex: 12000" value="<?php if(isset($entreprise['cp'] )) {echo $entreprise['cp'] ;}?>" pattern="[0-9]{5}" required>
							</div>
							
							</div>
							<!-- <label for="image">Importer une image :</label><br>
							<input type="file" name="image"><br><br>    -->
						</div>
						

						<div class="row caseCentrer">
							<div class=" col-md-8 col-sm-9 col-9 boutonDroite">
							
								<input name="action" type="hidden" value="modification">
								<input name="idEntreprise" type="hidden" value="<?php echo $entreprise_id; ?>">
								<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Enregistrer">
							
							</div>
						</div>
					</fieldset>
				</form>
			<!-- <input type="submit" name="submitButton" value="Ajouter l'Entreprise"> -->
			<!-- <input type="reset" value="Reset all info" tabindex="-1"> -->
			</details>
			<div class=" col-md-4 col-sm-3 col-3">
				<a href="Entreprise.php?retour=true" class="btn btn-outline-primary tailleMoyenne">Retour</a>
			</div>
			
			
    </div>

	



    </body>
</html>
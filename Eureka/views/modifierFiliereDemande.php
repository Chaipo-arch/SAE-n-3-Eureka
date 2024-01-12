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
	
	if (isset($_POST['action']) && $_POST['action'] == "deleteIntervention") {
		include("../services/AdminService.php");
		$idIntervenant= $_POST['idIntervenant'];
		$idfiliere= $_POST['idFiliere'];
		include("../services/FiliereService.php");
		
			deleteIntervenantFiliere(null,$idfiliere,$idIntervenant);
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['idIntervenant'])) {
		$intervenantId = $_POST['idIntervenant'];
		$intervenantsIntervenentions = getIntervenantFiliere(null, $intervenantId);
		
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;

	}
	if (isset($_POST['action']) && $_POST['action'] == "ajoutIntervention") {
		
		include("../services/AdminService.php");
		$filiere= $_POST['filiere'];
		include("../services/FiliereService.php");
		$id = getIdFiliere(getPDO(),$filiere);
		$nonPresent =true;
		var_dump($id);
		foreach($intervenantsIntervenentions as $intervention) {
			var_dump($intervention);
			if($intervention['id_filiere'] == $id) {
				$nonPresent = false;
			}
		}

		if($id != null  && $nonPresent) {
			ajoutFiliereIntervenant(null,$id,$_POST['idIntervenant']);
		}
		$intervenantsIntervenentions = getIntervenantFiliere(null, $intervenantId);
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
			<summary>Gérer les interventions</summary>
				<details>
					<summary>Supprimer les Interventions</summary>
					<?php 
					foreach($intervenantsIntervenentions as $intervention) { ?>
						<form action="modifierFiliereDemande.php" method="post">
							<fieldset>
							<div>
							
							<label for="first-name">filiere</label>
							<input type="text" name="filiere" value="<?php echo $intervention['field'] ;?>" readonly required >
							<br/>
							
							<input name="action" type="hidden" value="deleteIntervention">
							<input name="idIntervenant" type="hidden" value="<?php echo $intervenantId; ?>">
							<input name="idFiliere" type="hidden" value="<?php echo $intervention['id_filiere']; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne "  value="supprimer">
							
							<br/>
						</form>
					<?php }
				?>
				</details>

				<details>
					<summary>Ajouter une intervention</summary>
						<form action="modifierFiliereDemande.php" method="post">
							<fieldset>
							<div>
							<label for="first-name">Filiere demandé</label>
							<select name="filiere" class="form-control">
								<!-- option-->
								<?php 
									$filieres = displayAllFiliere();					
									foreach($filieres as $filiere) { ?>
										<option
										<?php if(isset($_POST['filiere'] ) && $_POST['filiere'] == $filiere['field']) {  echo " selected ";}?>
										> 
										<?php  echo $filiere['field'] ; ?>
										</option>
									<?php }  ?>
							</select>
							<br/>
							<input name="action" type="hidden" value="ajoutIntervention">
							<input name="idIntervenant" type="hidden" value="<?php echo $intervenantId; ?>">
							<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Ajouter">
							
							<br/>
						</form>
				</details>
				
			</details>
			<div class=" col-md-4 col-sm-3 col-3">
				<a href="Entreprise.php?retour=true" class="btn btn-outline-primary tailleMoyenne">Retour</a>
			</div>
			
			
    </div>

	



    </body>
</html>
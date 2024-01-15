<?php
	session_start();
	$filierePost ="Informatique";
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
    $pdo = getPDO();
	include("../services/FiliereService.php");
    if (isset($_POST['action']) && $_POST['action'] == "ajoutFiliere") {
		$designation= $_POST['designationFiliere'];
        $annee= $_POST['anneeFiliere'];
        $abreviation= $_POST['abreviationFiliere'];
		ajoutFiliere($pdo,$designation,$annee,$abreviation);
		


	}
	if (isset($_POST['action']) && $_POST['action'] == "modifierFiliere") {
		$id= intval($_POST['idFiliere']);
		$designation= $_POST['designationFiliere'];
        $annee= $_POST['anneeFiliere'];
        $abreviation= $_POST['abreviationFiliere'];
		$anneeInt = intval($annee, 10);
		
		if(modifierFiliere($pdo,$anneeInt,$designation,$abreviation,$id)){
			echo "La modification a était effectué";
		} else {
			echo "La modification n'a pas était effectué";
		}

	}
	if (isset($_POST['action']) && $_POST['action'] == "deleteFiliere") {
		$id= intval($_POST['idFiliere']);
		if(deleteFiliere($pdo,$id)) {
			echo "La suppression a était effectué";
		} else {
			echo "La suppression n'a pas était effectué";
		}

	}
	if (isset($_POST['filiereField']) ) {
		$filierePost = $_POST['filiereField'];
	}
	$filieres =  displayAllFiliere();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Modifier Filiere (ADMIN)</title>
		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>

    <div id="container" class="formAjoutEntreprise">
      <header>
        
      </header>
      
      

        <div class="row caseCentrer">
          <div class=" col-md-8 col-sm-9 titreCentrer">
            <h2 class="h2center">Modifier Filiere</h2>
          </div>
        </div>

		</br></br>
		<h2>Ajouter une Filiere : </h2>
		</br>
					<form action="GestionFiliere.php" method="post">
						<fieldset>
							<div>
							
							<label for="first-name">Designation de la filiere</label>
							<input type="text" name="designationFiliere"  required >
							<br/>
                            <label for="first-name">Annee de la filiere</label>
							<input type="text" name="anneeFiliere"  required >
							<br/>
                            <label for="first-name">Abreviation</label>
							<input type="text" name="abreviationFiliere"  required >
							<br/>
							
							<input name="action" type="hidden" value="ajoutFiliere">
							
							<input type="submit" class="btn btn-outline-primary" name="bouttonAjout" value="Ajouter">

							
							<br/><br/>
						</fieldset>
					</form>


				<details <?php if(isset($_POST['filiereField']) || isset($_POST['action']) && $_POST['action'] ="modifierFiliere" )  { echo "open" ; } ?>>
					<summary>Modifier des filieres</summary>
					<form action="GestionFiliere.php" method="post">
						<select name="filiereField" >
						<?php $filieresField = getFilieres($pdo); 
							 foreach($filieresField as $fil){

								echo '<option ';
								if($fil["field"] ==$filierePost || !isset($_POST['filiereField'])) {
									echo "selected" ;
								}
								echo '>'.$fil["field"].'</option>';
							  }					
							  echo "</select> ";
							?>
							<input type="submit" class="btn btn-outline-primary " value="trier">
					</form>
					<?php if(isset($filieres)) {
						foreach($filieres as $filiere) { 
							if( $filiere['field'] == $filierePost || !isset($_POST['filiereField'])) {?>
						
							<form action="GestionFiliere.php" method="post">
								<fieldset>
								<div>
								
								<label for="first-name">Designation de la filiere</label>
								<input type="text" name="designationFiliere" value="<?php echo $filiere['field'] ; ?>"  required >
								<br/>
								<label for="first-name">Annee de la filiere</label>
								<input type="text" name="anneeFiliere" value="<?php echo $filiere['year'] ; ?>" required >
								<br/>
								<label for="first-name">Abreviation</label>
								<input type="text" name="abreviationFiliere" value="<?php echo $filiere['abreviation'] ; ?>" required >
								<br/>
								
								<input name="action" type="hidden" value="modifierFiliere">
								<input type="hidden" name="idFiliere" value="<?php echo $filiere['id'] ; ?>"  >
								<input type="hidden" name="filiereField" value="<?php echo $filierePost ; ?>"  >
								<input type="submit" class="btn btn-outline-primary" value="Modifier">
								<br/>
								
								<br/>
								</fieldset>
							</form>
							<form action="GestionFiliere.php" method="post">
								<input name="action" type="hidden" value="deleteFiliere">
								<input type="hidden" name="idFiliere" value="<?php echo $filiere['id'] ; ?>"  >
								<input type="hidden" name="filiereField" value="<?php echo $filierePost ; ?>"  >
								<input type="submit" class="btn btn-outline-danger " value="Supprimer">
							</form>

				
					<?php } } } ?>
						
				</details>
				
			</details>
			<div class=" col-md-4 col-sm-3 col-3">
				<a href="Entreprise.php?retour=true" class="btn btn-outline-primary">Retour</a>
			</div>
			
			
    </div>

	



    </body>
</html>
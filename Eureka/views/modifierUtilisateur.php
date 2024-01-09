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

	
	if (isset($_POST['action']) && $_POST['action'] == "modification") {
		include("../services/AdminService.php");
		$userId = $_POST['idEtudiant'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$username = $_POST['username'];
		$mdp = $_POST['mdp'];
		$role = $_POST['role'];
		var_dump($role);
		$idRole = getIdRole(getPDO(),$role);
		var_dump($idRole);
		//$_SESSION['idEntrepriseAModifier'] = $entreprise_id;
		if($idRole != null) {
			modifUtilisateur(getPDO(),$nom,$prenom,$username,$mdp, $idRole, $userId);
		}

	}
	if (isset($_POST['idEtudiant'])) {
		$userId = $_POST['idEtudiant'];
		include("../services/ForumService.php");
		//$intervenants = getIntervenants();
		$utilisateur = getInfoEtudiant(getPdo(),$userId);
		$role = getRole(getPDO(),$utilisateur['id_role']);
		var_dump($role);
		var_dump($utilisateur);
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Modifier Utilisateur (ADMIN)</title>
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
            <h2 class="h2center">Modifier Utilisateur</h2>
          </div>
        </div>
			<details>
			<summary>Modifier Informations Utilisateur</summary>
				<form action="modifierUtilisateur.php" method="post">
					<fieldset>
						<div>
							
							<label for="first-name">Nom de L'etudiant*</label>
							<input type="text" name="nom" value="<?php echo $utilisateur['nom'] ;?>" required >
							<br/>
							<label for="first-name">Prenom de L'etudiant*</label>
							<input type="text" name="prenom" value="<?php echo $utilisateur['prenom'] ;?>" required>
							<br/>

							<label for="first-name">Username*</label>
							<input type="text" name="username" value="<?php echo $utilisateur['Username'] ;?>" required>
							<br/>

							<label for="first-name">Mot de Passe*</label>
							<input type="text" name="mdp" value="<?php echo $utilisateur['password'] ;?>" required>
							<br/>
							<label for="first-name">Role*</label>
							<select name="role"  class="form-control">
								<!-- option-->
								<?php 
									$roles = displayAllRole();		
									var_dump($roles);						
									foreach($roles as $roleUti) { ?>
										<option
										<?php if(isset($role ) && $role == $roleUti['designation']) {  echo " selected ";}?>
										> 
										<?php  echo $roleUti['designation'] ; ?>
										</option>
									<?php }  ?>
							</select>
							
							<br/>
							<!--<label for="first-name">Filiere</label>
							<select name="filiere" type="submit">-->
								
								<?php 
								/*
									$filieres = displayAllFiliere();										
									foreach($filieres as $filiere) { ?>
										<option
										<?php if(isset($_POST['filiere']) && $_POST['filiere'] == $filiere['field']) {  echo " selected ";}?>
										> 
										<?php  echo $filiere['field'] ; ?>
										</option>
									<?php }  */?>
							<!--</select>
							<br/>-->
							

							
							
							
							<!-- <label for="image">Importer une image :</label><br>
							<input type="file" name="image"><br><br>    -->
						</div>
						

						<div class="row caseCentrer">
							<div class=" col-md-4 col-sm-3 col-3">
							<a href="GestionUtilisateur.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
							</div>
							<div class=" col-md-8 col-sm-9 col-9 boutonDroite">
							
								<input name="action" type="hidden" value="modification">
								<input name="idEtudiant" type="hidden" value="<?php echo $userId; ?>">
								<input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Enregistrer">
							
							</div>
						</div>
					</fieldset>
				</form>
			<!-- <input type="submit" name="submitButton" value="Ajouter l'Entreprise"> -->
			<!-- <input type="reset" value="Reset all info" tabindex="-1"> -->
			</details>
			<div class=" col-md-4 col-sm-3 col-3">
				<a href="GestionUtilisateur.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
			</div>
			
			
    </div>

	



    </body>
</html>
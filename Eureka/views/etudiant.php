<?php
session_start();
include("../services/AdminService.php");
include("../services/EtudiantService.php");
include("../services/FiliereService.php");
include("../services/UserService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php');
    exit();
}

if ($_SESSION['role'] == "Etudiant") {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: forum.php');
    exit();
}
require('../fonctions/gestionBD.php');
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoie vers l'index
		header('Location: ../index.php');
		exit();
	} 
$pdo = getPDO();
if(isset($_POST['action'])&& $_POST['action'] == "supprimerEtudiant" && $_SESSION['role'] == "Admin") {
	$idE = $_POST['idUserS'];
	deleteEtudiant($pdo, $idE);
}
$filieres = getFilieres($pdo);
	
	if(!isset($_SESSION['filiere'])) {
		$_SESSION['filiere'] = 'Toutes';
	 } else if(isset($_GET['filiere'])){
		$_SESSION['filiere'] = $_GET['filiere'];
	 }
	 if(!isset($_GET['recherche'])) {
		$etudiants = getStudentWithsouhaits($pdo); // TODO ajout avec la filiere
	} else {
		$etudiants = getStudentWithsouhaits($pdo); // TODO la recherche particuliere 
		$saisies = $_GET['recherche'];
	}
	//$etudiants = getStudentWithsouhaits($pdo);
	
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Etudiants</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">
		<link href="../css/HeaderCss.css" rel="stylesheet">
		<link href="../css/EtudiantCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>
	<body>
	<?php 
	include("../fonctions/viewHelper.php");
	headerHelper();
    ?>
	</br>
	</br>
	</br>
	<div class="container separation">
		<div class="col-md-12">
			<form action="etudiant.php" method="get">
				<div class= "row centre">
					<div class="col-md-3 centre">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Etudiant">
						<select name="filiere" type="submit">
							<?php if ($_SESSION['role'] != "Etudiant") { ?>
								
							<option>Toutes</option>
							<?php } ?>
							<!-- option-->
							<?php 
							if(isset($filieres)) {

								foreach($filieres as $filiere) { ?>
									<option
									<?php if($_SESSION['filiere'] == $filiere['field']) {  echo " selected ";}?>
									> 
									<?php  echo $filiere['field'] ; ?>
									</option>
								<?php }  ?>
								<?php 
								if($_SESSION['role'] == "Etudiant") {
									echo '<option>'. $filieres. '</option>';
								}
							}?>
						</select>
					</div>
					<div class="col-md-2 centre">
						<input type="submit" value="valider">
					</div>
					<div class="col-md-6 centre">
						<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
					</div>
				</div>
			</form>
			<?php if($_SESSION['role'] == 'Admin'){ ?>
				<div class="col-md-12 centre">
					<form class="form my-1 my-lg-1" action="ajoutEtudiant.php" method="get">
						<input name="action" type="hidden" value="ajouterEtudiant">
						<input name="controller" type="hidden" value="Admin">
						<input class="btn btn-form-control mr-sm-1 btn-outline-dark" type="submit" value="+">
					</form>	
				</div>
			<?php } ?>
		</div>
		</br>
		<?php 

		if(isset($etudiants)&& $etudiants) {
			foreach($etudiants as $etudiant) {
				if($_SESSION['filiere'] == "Toutes" || $_SESSION['filiere']== $etudiant['field'] ) { ?>
				
				<div class="col-12">
					<div class="card mb-4 shadow-sm d-flex flex-row">
						<div class="card-body d-flex flex-column justify-content-between">
							<p class="card-text">
							<?php echo  $etudiant['nom']. " ". $etudiant['prenom'];?>
							</p>
							<form action="Entreprise.php" method="POST">
								<input name="controller" type="hidden" value="Etudiant">
								<input  name="action" type="hidden" value="afficherSouhait">
								<input type="hidden" name="idUserS" value="<?php echo $etudiant['id'] ;?>">
								<?php 
								/*$i = 0;
								foreach($etudiant['souhait'] as $souhait) {?>
									<input type="hidden" name="souhait<?php echo $i ;?>" value="<?php echo $souhait;?>">
								<?php $i = $i+1;} */?>
								
								<input type="submit" value="Voir souhaits">
							</form>
						</div>
						<div class="row">
							<?php if ($_SESSION['role'] == "Admin") { ?>
								<form action="modifierUtilisateur.php" method="post">
									<div class="btn-group mt-2 col-md-12">
										<input type="hidden" name="idEtudiant" value="<?php echo $etudiant['id'] ;?>">
										<input name="action" type="hidden" value="modifierEtudiant">
										<button type="button submit" class="btn btn-sm btn-outline-secondary">
											Modifier Etudiant
										</button>
									
									</div>
								</form>
								<form action="etudiant.php" method="post">
									<div class="btn-group mt-2 col-md-12">
										<input type="hidden" name="idUserS" value="<?php echo $etudiant['id'] ;?>">
										<input name="action" type="hidden" value="supprimerEtudiant">
										<button type="button submit" class="btn btn-sm btn-outline-secondary">
											Supprimer Etudiant
										</button>
									
									</div>
								</form>
								
							<?php }?>
						</div>
					</div>
				</div>
				
		<?php } } }?>
	</div>
    <?php footerHelper();
	 ?>
  </body>
</html>
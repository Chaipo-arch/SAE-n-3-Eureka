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
require('../fonctions/generation.php');
include("../services/ForumService.php");

// Connexion à la BD
if (!connecteBD($erreur)) {
	// Pas de connexion à la BD, renvoie vers l'index
	header('Location: ../index.php');
	exit();
} 
$pdo = getPDO();
$caracteristiques = getForumCaracteristiques($pdo);
$dateLDeppasse = dateLimiteDepassee($pdo);
if(isset($_POST['action'])&& $_POST['action'] == "generation") {
	$duree = $_POST['duree'];
	$type = $_POST['type'];
	generationPlanningEntier($pdo,$duree,$type);

}
 if($dateLDeppasse && $_SESSION['role'] == "Etudiant") {
	$message = "Vous pouvez encore effectuer des souhaits. Une fois la date limite dépassée votre planning sera affiché quand il sera généré et affiché";
 }

if($_SESSION['role'] == "Etudiant" ) { 
	$_POST['action'] = "voirEtudiant";
	$_POST['idEtudiant'] = $_SESSION['IdUser']; 
	} 
if(isset($_POST['action'])&& $_POST['action'] == "voirEtudiant" && !$dateLDeppasse) {

	if ($_SESSION['role'] == "Admin" ) {
		$admin = true ;
	} else {
		$admin = false;
	}
	$idEtudiant = $_POST['idEtudiant'];
	$rdv = voirPlanning($pdo,$idEtudiant,$admin);
	if($rdv == null && $_SESSION['role'] == "Etudiant" ) {
		$message = "Vous ne possédez pas de planning";
	} else if ($rdv == null) {
		$message = "Pas de planning actuellement disponible pour cet étudiant";
	}

}
if(isset($_POST['action'])&& $_POST['action'] == "voirEntreprise") {
	if ($_SESSION['role'] == "Admin" ) {
		$admin = true ;
	} else {
		$admin = false;
	}
	$idEntreprise = $_POST['idEntreprise'];
	$rdv = voirPlanningEntreprise($pdo,$idEntreprise,$admin);
	if($rdv == null) {
		$message = "Pas de planning actuellement disponible pour cette entreprise";
	}

}
if(isset($_POST['action'])&& $_POST['action'] == "validerTousPlanning") {
	ValiderTousLesPlanning($pdo);
	$message = "Tous les plannings ont étaient valider";
}
if(isset($_POST['action'])&& $_POST['action'] == "validerPlanningActuelle") {
	$cat = htmlspecialchars($_POST['cat']);
	$idP = htmlspecialchars($_POST['idP']);
	ValiderPlanning($pdo,$idP,$cat);
	$message = "Le planning à était validé";
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
		<link href="../css/EntrepriseCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
    <?php 
    include("../fonctions/viewHelper.php");
    headerHelper();
    ?>
	</br>
	<?php if ($_SESSION['role'] == "Admin" ) { ?>
	<div class="container separation">
		<div class='col-md-12 centre'>
            <form action="planning.php" method="post">
				<input type="hidden" name="duree"  value="00:00:00">
				<input type="hidden" name="type"  value="1">
				<input type="hidden" name="action"  value="generation">
                <input type="submit"  class="btn-planning btn-primary btn" value="Generation de tous les plannings " <?php if ($dateLDeppasse)  { echo 'disabled' ; }?>>
            </form>
			</div>
		<?php } ?>
		
		<?php if (isset($message) && !isset($rdv)) {
			echo "<div class='container separation'><div class='col-md-12 centre noir'><h2>$message</h2></div></div>";
			} else if (isset($rdv ) && !empty($rdv)){
				if(isset($idEtudiant)) {
					$idP = $idEtudiant;
					$categorie = 1;
				} else {
					$idP = $idEntreprise;
					$categorie = 2;
				}
				
				if ($_SESSION['role'] == "Admin" && $rdv[0]['est_valide'] == 0 ) { ?>
			<div class="col-md-12 centre">
				<form action="planning.php" method="post">
					<input type="hidden" name="action"  value="validerTousPlanning">
					<input type="submit"  value="Valider Tous Les Plannings">
				</form>
			</div>
			<div class="col-md-12 centre">
				<form action="planning.php" method="post">
					<input type="hidden" name="action"  value="validerPlanningActuelle">
					<input type="hidden" name="idP"  value="<?php echo $idP; ?>"/>
					<input type="hidden" name="cat"  value="<?php echo $categorie; ?>"/>
					<input type="submit"  value="Valider le planning actuelle">
				</form>
			</div>
			<?php } ?>
				<?php foreach($rdv as $rendezvous) {?>	
					<div class="col-md-12 centre my-styled-box">
						<?php echo $rendezvous['Designation'];?></br><?php echo $rendezvous['name'];?></br><?php echo $rendezvous['Heure_debut'];?></br><?php echo $rendezvous['Heure_fin'];?></br></br>
					</div>

			<?php } }  ?>
			
		</div>
		
        <?php footerHelper(); ?>
  </body>
</html>
<?php
session_start();
include("../services/AdminService.php");
include("../services/EntrepriseService.php");
include("../services/EtudiantService.php");
include("../services/FiliereService.php");
include("../services/UserService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php');
    exit();
}
$host = 'localhost';
$port = '3306';
$db = 'eureka';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true
    ];

$pdo = new PDO($dsn, $user, $pass, $options);
if(isset($_POST['action'])&& $_POST['action'] == "supprimerEntreprise" && $_SESSION['role'] == "Admin") {
	$idE = $_POST['idEntreprise'];
	deleteEntreprise($pdo, $idE);

}
if(isset($_GET['action'])&& $_GET['action'] == "deleteSouhaitEtudiant") {
	$idE = $_GET['idEntreprise'];
	deleteSouhait($pdo, $idE, $_SESSION['IdUser']);
    getSouhait($pdo, $_SESSION['IdUser']);

}
if(isset($_GET['action'])&& $_GET['action'] == "setSouhaitEtudiant") {
	$idE = $_GET['idEntreprise'];
	createSouhait($pdo, $idE, $_SESSION['IdUser']);
	getSouhait($pdo, $_SESSION['IdUser']);

}
if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
	$filieres = getFilieres($pdo);
	
	if(!isset($_SESSION['filiere'])) {
		$_SESSION['filiere'] = 'Toutes';
	 } else if(isset($_GET['filiere'])){
		$_SESSION['filiere'] = $_GET['filiere'];
	 }
} else {
	$id = $_SESSION['IdUser'];
	$filieres = getStudentFiliere($pdo,$id);
	$_SESSION['filiere'] = $filieres;
}
if(!isset($_GET['recherche'])) {
	$saisies = '';
} else {
	$saisies = $_GET['recherche'];
}
if(isset($_GET['action'])&& $_GET['action'] == "afficherSouhait") {
	$idUser = $_POST['idUserS'];
	var_dump($_POST);
	$souhaits = getSouhaitE($pdo,$idUser);
	$entreprises = array();
	foreach($souhaits as $souhait) {
		$entreprises[] = getEntreprise($pdo,$souhait);
	}
}else {
	$entreprises = rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Entreprises</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
		<link href="../css/HeaderCss.css" rel="stylesheet">
 		 <link href="../css/EntrepriseCss.css" rel="stylesheet">
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
	</head>

	<body>
		<?php 
		include("../fonctions/viewHelper.php");
		headerHelper();

		?>
		</br>
		
		<div class="container separation">
		
			<div class="col-md-12">
				
					<form action="Entreprise.php" method="get">
						<div class= "row centre">
						<div class="col-md-3 centre">
							<select name="filiere" type="submit">
								<?php if ($_SESSION['role'] != "Etudiant") {?>
									
								<option>Toutes</option>
								<?php } ?>
								<!-- option-->
								
								<?php 
								var_dump($_GET);
								if(isset($filieres)) {
									var_dump($_GET);
										
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
					<!-- affichage de l'ajout d'entreprise si admin !-->
					<?php if($_SESSION['role'] == 'Admin'){ ?>
						<div class="col-md-12 centre">
							<form class="form my-1 my-lg-1" action="ajoutEntreprise.php" method="Post">
								<input class="btn btn-form-control mr-sm-1 btn-outline-dark" type="submit" value="+">
							</form>	
						</div>
					<?php } ?>
					
			</div>
		
			<?php 
			if(isset($entreprises)) {
				foreach($entreprises as $entreprise) {
					?>
					<p>
					<div class="col-12">
						<div class="card mb-4 shadow-sm d-flex flex-row">
							<img src="<?php echo $entreprise['logo'] ; ?>">
							<div class="card-body d-flex flex-column justify-content-between">
								<p class="card-text">
									Nom entreprise : 
									<?php echo $entreprise['Designation'] ;?>
								</p>
									Presentation : 
									<?php echo $entreprise['presentation'] ;?>
								<div class="btn-group mt-2">
									<button type="button" class="btn btn-sm btn-outline-secondary">
										Nous Découvrir
									</button>
									
									<?php if($_SESSION['role'] == "Etudiant"  ) { 
											$passage = false;
											foreach($_SESSION['souhait'] as $souhait) {
												if($souhait == $entreprise['Id']) {
													$passage = true;?>
									
													<form action ="Entreprise.php" methode="get">
														<input name="action" type="hidden" value="deleteSouhaitEtudiant">
														<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">
														<button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
															
															Annuler Rendez Vous
														</button>
													</form>
												<?php } ?>   
													
										<?php  } ?>
										<?php if (!$passage) {?>
											<form action ="Entreprise.php" methode="get">
											<input name="action" type="hidden" value="setSouhaitEtudiant">
											<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">
											<input name="controller" type="hidden" value="Entreprise">
											
											<button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
												
												Rendez Vous
											</button>
											</form>
									<?php } } ?>
								</div>
								<div class="row">
								<?php if ($_SESSION['role'] == "Admin") { ?>
									<form action="modifierEntreprise.php" method="post">
										<div class="btn-group mt-2 col-md-12">
										<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">

										<button type="button submit" class="btn btn-sm btn-outline-secondary">
											Modifier Entreprise
										</button>
									
										</div>
									</form>
									<form action="Entreprise.php" method="post">
										<div class="btn-group mt-2 col-md-12">
										<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">
										<input name="action" type="hidden" value="supprimerEntreprise">
										<button type="button submit" class="btn btn-sm btn-outline-secondary">
											Supprimer Entreprise
										</button>
									
										</div>
									</form>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					</p>
			<?php } }?>
	</div>
	
    <?php footerHelper();
	 ?>
	 
  </body>
</html>
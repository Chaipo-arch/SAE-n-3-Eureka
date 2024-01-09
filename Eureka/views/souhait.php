<?php
session_start();
include("../services/EntrepriseService.php");
include("../services/UserService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php?action=renvoi');
    exit();
}
if ($_SESSION['role'] == "Gestionnaire" | $_SESSION['role'] == "Admin" ) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: forum.php');
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
if(isset($_GET['action'])&& $_GET['action'] = "deleteSouhaitEtudiant") {
	$idE = $_GET['idEntreprise'];
	deleteSouhait($pdo, $idE, $_SESSION['IdUser']);
    getSouhait($pdo, $_SESSION['IdUser']);

}
$entreprises = array();
foreach($_SESSION['souhait'] as $souhait ) {
            $liste = getEntreprise($pdo,$souhait);
            $entreprises[] = $liste;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Eureka - Vos Souhaits</title>

        <!-- Bootstrap CSS -->
        <link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

        <!-- Lien vers mon CSS -->
        <link href="../css/monStyle.css" rel="stylesheet">
        <link href="../css/HeaderCss.css" rel="stylesheet">

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
	</br>
	
		<?php 
		if(isset($entreprises)) {
			foreach($entreprises as $entreprise) {?>
				
					<p>
					<div class="col-12">
						<div class="card mb-4 shadow-sm d-flex flex-row">
							<img src="<?php echo $entreprise['logo'] ; ?>">
							<div class="card-body d-flex flex-column justify-content-between">
								<p class="card-text">
								<?php echo $entreprise['Designation'] ;?>
								</p>
								<?php echo $entreprise['presentation'] ;?>
								<div class="btn-group mt-2">
									<button type="button" class="btn btn-sm btn-outline-secondary">
										Nous Découvrir
									</button>
									
									<?php if($_SESSION['role'] == "Etudiant"  ) { 
										$passage = false;
										foreach($_SESSION['souhait'] as $souhait) {
											if($souhait == $entreprise['id']) {
												$passage = true;?>
									
												<form action ="souhait.php" methode="get">
													<input name="action" type="hidden" value="deleteSouhaitEtudiant">
													<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['id'] ; ?>">
													<input name="controller" type="hidden" value="Souhait">
													<button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
														Annuler Rendez Vous
													</button>
												</form>
												  
													
									<?php  } } } ?>
										
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
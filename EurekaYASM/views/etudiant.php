<?php
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php?action=renvoi');
    exit();
}
if ($_SESSION['role'] == "Etudiant") {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Etudiants</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="css/monStyle.css" rel="stylesheet">
		<link href="css/HeaderCss.css" rel="stylesheet">
		<link href="css/EtudiantCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>
	<body>
	<?php 
	include("fonctions/viewHelper.php");
	headerHelper();
    ?>
	</br>
	</br>
	</br>
	<div class="container separation">
		<div class="col-md-12">
			<div class= "row centre">
				<form action="index.php" method="get">
					<div class="col-md-3 centre">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
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
				</form>
			</div>
		</div>
		</br>
	
		<?php 
		if(isset($etudiants)) {
			var_dump($etudiants);
			foreach($etudiants as $etudiant) {
				var_dump($etudiant);?>
				<p>
				<div class="col-12">
					<div class="card mb-4 shadow-sm d-flex flex-row">
						<img src="<?php echo  ""; ?>">
						<div class="card-body d-flex flex-column justify-content-between">
							<p class="card-text">
							<?php echo  "";?>
							</p>
							<?php echo "" ;?>
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
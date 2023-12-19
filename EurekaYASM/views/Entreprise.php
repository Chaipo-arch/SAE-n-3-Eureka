<?php

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: ../index.php?action=renvoi');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Entreprises</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="css/monStyle.css" rel="stylesheet">
		
		<link href="css/EntrepriseCss.css" rel="stylesheet">
		<link href="css/HeaderCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
	<?php 
	include("fonctions/viewHelper.php");
	//headerHelper();
    ?>
	</br>
	
	<div class="container separation">
	<form action="index.php" method="get">

		<div class="col-md-4 centre">
		<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
						<select name="filiere" type="submit">
							<option>Toutes</option>
							<!-- option-->
							<?php 
							if(isset($filieres)) {
								var_dump($filieres);
								foreach($filieres as $filiere) { ?>
								<option
								<?php if($_SESSION['filiere'] == $filiere) {  echo " selected ";}?>
								> 
								<?php  echo $filiere['field'] ; ?>
								</option>
							<?php } } ?>
						</select>
		</div>
		<div class="col-md-6 centre">
		<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
					
		</div>
		<div class="col-md-2 centre">
		<input type="submit" value="valider">
		</div>	
		
		
		<div class="col-md-12">
			<div class= "row centre">
				<form action="index.php" method="get">
					<div class="col-md-4 centre">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
						<select name="filiere" type="submit">
							<option>Toutes</option>
							<!-- option-->
							<?php 
							if(isset($filieres)) {
								var_dump($filieres);
								foreach($filieres as $filiere) { ?>
								<option
								<?php if($_SESSION['filiere'] == $filiere) {  echo " selected ";}?>
								> 
								<?php  echo $filiere['field'] ; ?>
								</option>
							<?php } } ?>
						</select>
					</div>
					<div class="col-md-2 centre">
						<input type="submit" value="valider">
					</div>
						
					
					
					<div class="col-md-6 centre">
						<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
					
					</div>
				</form>
				<!-- affichage de l'ajout d'entreprise si admin !-->
				<?php if($_SESSION['role'] == 'Admin'){ ?>
				<div class="col-md-2 centre">
					<form class="form my-1 my-lg-1" action="index.php" method="get">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
						<input class="btn btn-form-control mr-sm-1 btn-outline-dark" type="submit" value="+">
					</form>	
				</div>
				<?php } ?>
			</div>
		</div>
		<?php 
		if(isset($entreprises)) {
		
			foreach($entreprises as $entreprise) {
				?>
				<p>
				<div class="col-12">
					<div class="card mb-4 shadow-sm d-flex flex-row">
						<img src="<?php echo $entreprise['logo'] ; ?>">
						<div class="card-body d-flex flex-column justify-content-between">;
							<p class="card-text">
							<?php echo $entreprise['designation'] ;?>
							</p>
							<?php echo $entreprise['presentation'] ;?>
							<div class="btn-group mt-2">
								<button type="button" class="btn btn-sm btn-outline-secondary">
									Nous Découvrir
								</button>
								<button type="button" class="btn btn-sm btn-outline-secondary ml-1">
									Rendez vous
								</button>
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
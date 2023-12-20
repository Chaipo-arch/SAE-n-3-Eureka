<?php

if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php?action=renvoi');
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
		
		<link href="css/EntrepriseCss.css" rel="stylesheet">
		<link href="css/HeaderCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
		<?php 
		include("fonctions/viewHelper.php");
		headerHelper();

		?>
		</br>
		
		<div class="container separation">
		
			<div class="col-md-12">
				
					<form action="index.php" method="get">
						<div class= "row centre">
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
						</div>
					</form>
					<!-- affichage de l'ajout d'entreprise si admin !-->
					<?php if($_SESSION['role'] == 'Admin'){ ?>
						<div class="col-md-12 centre">
							<form class="form my-1 my-lg-1" action="index.php" method="get">
								<input name="action" type="hidden" value="ajouterEntreprise">
								<input name="controller" type="hidden" value="Admin">
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
												if($souhait == $entreprise['Id']) {
													$passage = true;?>
									
													<form action ="index.php" methode="get">
														<input name="action" type="hidden" value="deleteSouhaitEtudiant">
														<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">
														<input name="controller" type="hidden" value="Entreprise">
														
														<button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
															
															Annuler Rendez Vous
														</button>
													</form>
												<?php } ?>   
													
										<?php  } ?>
										<?php if (!$passage) {?>
											<form action ="index.php" methode="get">
											<input name="action" type="hidden" value="setSouhaitEtudiant">
											<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['Id'] ; ?>">
											<input name="controller" type="hidden" value="Entreprise">
											
											<button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
												
												Rendez Vous
											</button>
											</form>
									<?php } } ?>
								</div>
								<?php if ($_SESSION['role'] == "Admin") { ?>
									<form action="index.php" method="post">
										<div class="btn-group mt-2 col-md-12">
										<input name="action" type="hidden" value="modifierEntreprise">
										<input name="controller" type="hidden" value="Admin">
										<button type="button submit" class="btn btn-sm btn-outline-secondary">
											Modifier Entreprise
										</button>
									
										</div>
									</form>
								<?php } ?>
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
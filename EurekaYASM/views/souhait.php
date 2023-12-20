<?php

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title></title>

        <!-- Bootstrap CSS -->
        <link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

        <!-- Lien vers mon CSS -->
        <link href="css/monStyle.css" rel="stylesheet">
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
									
												<form action ="index.php" methode="get">
													<input name="action" type="hidden" value="deleteSouhaitEtudiant">
													<input name="idEntreprise" type="hidden" value="<?php echo $entreprise['id'] ; ?>">
													<input name="controller" type="hidden" value="Entreprise">
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
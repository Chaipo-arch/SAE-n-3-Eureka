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
		<link href="css/monStyle.css" rel="stylesheet">
		
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
			<div class= "row">
				<div class="col-md-2">
					<form action="index.php">
						<input name="controller" type="hidden" value="Entreprise">
						<select name="filiere">
							
						</select>
					</form>
				</div>
				<div class="col-md-10">
					<form class="form my-1 my-lg-1" action="index.php" method="get">
						<input name="action" type="hidden" value="recherche">
						<input name="controller" type="hidden" value="Entreprise">
						<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
					</form>	
				</div>
			</div>
		</div>
		<table>
			<?php
				$listeDesEntreprises = $_SESSION['listeEntrepriseRecherche'];		     
				foreach($listeDesEntreprises as $key => $entreprise) { //affichage de toutes les entreprises
			?>
				<tr>
					<div class="col-md-12 entrepriseCase">
						<div class= "row">
							<div class="col-md-2">
								<td><?php echo $entreprise['logo']; ?></td>
							</div>
							<div class="col-md-2">
								<td><?php echo $entreprise['designation']; ?></td>	
							</div>
							<div class="col-md-2">
								<td><?php echo $entreprise['secteur']; ?></td>	
							</div>
							<div class="col-md-6">
								<td><button class="accordion-btn">voir détails</button></td>
							</div>
						</div>
					</div>	
				</tr>
				<tr>
					<div class="col-md-12">
						<td colspan="12">
							<div class="accordion-content">
								<p><?php echo $entreprise['presentation']; ?></p>
							</div>
						</td>			
					</div>					
				</tr>
			<?php } ?>
		</table>
    <?php footerHelper(); ?>
  </body>
</html>
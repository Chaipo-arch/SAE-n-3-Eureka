<?php
	session_start();	
	// Intégration des fonctions qui seront utilisées pour récupérer 
	// les différentes informations stocké dans le BD
	require('../fonctions/BD.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Eureka</title>
	<meta charset ="utf-8"/>
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="../css/Style.css" rel="stylesheet"/>
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
	<script src="../js/scriptJS.js"></script>
</head>	
<body>
	<div class="container">
		<div class="row entete">
			<div class="col-md-2">
				<?php // todo header enzo>?>
			</div>			
			<form action="pageInformationEntreprise.php" method="post">
				<div class="col-md-2">
					<input type="text" name="entrepriseRecherche" placeholder="Taper nom de l'entreprise"/>
				</div>
				<div class="col-md-2">
					<button type="button">Rechercher l'entreprise</button>2
				</div>
			</form>							
		</div>
	</div>	
	<div class="container">
		<table>
			<?php
			if($_POST['entrepriseRecherche'] || $_POST['entrepriseRecherche'] == null){			
				$listeDesEntreprises = listeDesEntreprise(); 
			} else {
				$listeDesEntreprises = listeEntrepriseRecherche($_POST['entrepriseRecherche']);
			}     
				foreach($listeDesEntreprises as $key => $entreprise) { //affichage de toutes les entreprises
			?>
				<tr>
					<td><?php echo $entreprise['designation']; ?></td>
					<td><?php echo $entreprise['secteur']; ?></td>
					<td><?php echo $entreprise['logo']; ?></td>
					<td><button class="accordion-btn">voir détails</button></td>
				</tr>
				<tr>
					<td colspan="12">
						<div class="accordion-content">
							<p><?php echo $entreprise['presentation']; ?></p>
						</div>
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
</body>
</html>

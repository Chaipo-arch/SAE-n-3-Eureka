<?php
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	
	// Test si on a bien envoyé un numéro de compte pour afficher le détail
	if (!isset($_POST['noCompte']))  {
		// No de compte pas envoyé, on retourne à la liste des comptes
		header('Location: comptes.php');
		exit();
	}

	// Intégration des fonctions qui seront utilisées pour les acces à la BD
	require('../fonctions/gestionBD.php');  // Fonctions pour la base de données
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoi vers l'index
		header('Location: ../index.php');
		exit();
	} 
	
	$infosCompte=detailCompte($_POST['noCompte']); 	// Récupération des informations du compte 
	$image=$infosCompte['image'];					// Variable image du compte
	$noCompte=$infosCompte['noCompte'];				// Variable Numéro du compte
	$libelleCompte=$infosCompte['libelle'];			// Variable libellé du compte
?>
				
				
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>IUT BANK - Détail du compte</title>
		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">
		
		<!-- Lien vers CSS fontawesome -->
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	
	</head>

	<body>
  
		<div class="container">
			<div class="row entete"><!--- Ligne d'Entete -->
				<div class="col-md-3">
					<!--- Colonne Logo -->
					<a href="../index.html"><img src="../images/logo.jpg" alt="Logo IUT BANK" id="imageHaut"/></a>
				</div>
				<div class="col-md-9">
					<!--- Colonne Titre -->
					<h1>Ma Banque en ligne<br/>IUT BANK ONLINE</h1>
				</div>
			</div>
			<div class="row ligne"><!--- Ligne d'Accueil -->
				<div class="col-md-12">
					<!--- Cellule client -->
					<h1>
						-- Bienvenue <?php echo $_SESSION['nomClient'];?> --
					</h1>
					<h2>	
						Vous pourrez grâce à cette interface voir le détail de vos comptes et faire toutes vos opérations à distance.
					</h2>
				</div>
			</div>
			<div class="row ligne texte"><!--- Ligne d'Accueil -->
				<div class="col-md-2">
					<img src="../images/<?php echo $image;?>" class="imgCompteHorizontal" alt="Image compte courant"/>
				</div>
				<div class="col-md-10">
					<br/>
					<?php 
						echo "Compte : ".$noCompte." - ".$libelleCompte;
					?>

				</div>
			</div>	
			

			<div class="row ligne texte">
				<div class="col-12">
					<table class="largeur100 table table-striped"> <!--- Table contenant la liste des comptes avec leurs solde -->
						<tr> <!--- lignes Compte -->
							<!--- Contenu -->
							<td>
								<!--- Colonne date -->
								Date
							</td>
							<td>
								<!--- Colonne Type -->
								Type

								<form action="detailCompte.php" method="post">
									<select name="typeEcritures">
									
									<?php
										// Constitution du select pour afficher les filtres
										// Ajout de l'option Tous en première ligne
										echo "<option value='TS'";
										if (isset($_POST["typeEcritures"]) and $_POST["typeEcritures"]=="TS") { echo " selected";}
										echo ">Tous</option>";
										
										$typesEcritures=typesDesEcritures(); // Récupération des types existants
										
										// Boucle sur les types pour construire le select
										foreach($typesEcritures as $type =>$typeEcriture) {
											$valeur=$type;	
											$libelle=$typeEcriture;
											$tableauCorrespondance[$valeur]=$libelle; // Permettra de faire la correspondance entre le type et le vrai libellé dans la liste des écritures
											echo "<option value='".$valeur."'";
											if (isset($_POST["typeEcritures"]) and $_POST["typeEcritures"]==$valeur) { echo " selected";}
											echo ">".$libelle."</option>";
										}

									?>
									</select>
									<input class="btn btn-info" type="submit" value="Filtrer">
									<!-- Rajout du numéro de compte en hidden pour ne pas le perdre en utilisant ce formulaire -->
									<input name="noCompte" type="hidden" value="<?php echo $_POST['noCompte']; ?>">
									
								</form>
							</td>
							<td>
								<!--- Colonne libelle -->
								Libellé
							</td>
							<td>
								<!--- Colonne Débit -->
								Débit
							</td>	
							<td>
								<!--- Colonne Crédit -->
								Crédit
							</td>	
							
							<?php 
								if (!isset($_POST["typeEcritures"]) or (isset($_POST["typeEcritures"]) and $_POST["typeEcritures"]=="TS")) {
									// Permet de ne pas afficher la colonne solde quand on a un filtre et qu'il est différent de Tous
									echo "<td>Solde</td>";
								}
							?>						
						</tr>
						<?php 
						$soldeProgressif=0; // Variable permettant d'afficher le solde progressif
						
						if (isset($_POST["typeEcritures"])) {$typeAafficher=$_POST["typeEcritures"];} else {$typeAafficher="TS";}
						
						// Récupération des écritures pour le compte et le filtre si il est positionné
						$listeEcritures=listeDesEcrituresdUnCompte($_POST['noCompte'],$typeAafficher); 
						
						// Boucle afficher les écritures 
						foreach($listeEcritures as $ecriture) {
							$date=$ecriture['date'];
							$type=$ecriture['type']; 
							$typeEnClair=$ecriture['typeEnClair']; 
							$libelle=$ecriture['libelle'];
							$debit=$ecriture['debit'];
							$credit=$ecriture['credit'];
							
							echo "<tr>";
							echo "<td>".$date."</td>";
							echo "<td>".$typeEnClair."</td>";
							echo "<td>".$libelle."</td>";
							if (floatval($debit)!=0) {
								echo "<td class='enRouge'>".number_format(floatval($debit),2)."</td>"; 
							} else {
								echo "<td></td>";
							}
							if (floatval($credit)!=0) {
								echo "<td class='enVert'>".number_format(floatval($credit),2)."</td>"; 
							} else {
								echo "<td></td>";
							}
							
							if (!isset($_POST["typeEcritures"]) or (isset($_POST["typeEcritures"]) and $_POST["typeEcritures"]=="TS")) {
								// Permet de ne pas afficher la colonne solde quand on a un filtre et qu'il est différent de Tous
								$soldeProgressif+=floatval($credit)-floatval($debit);
								$classe="enVert";
								if ($soldeProgressif<=0) {$classe="enRouge";}
								echo "<td class='".$classe."'>".number_format(floatval($soldeProgressif),2)."</td>";
							}
							echo "</tr>";

						}							
						
						?>
			
					</table>
				</div>
			</div>

			<div class="row ligne">
				<div class="col-9">
				<!--- Ligne Menu Bas -->
					<div class="row"> 
						<div class="col-4">
							<br/><a href="contact.html"><button type="button" class="btn btn-primary btn-block">Nous contacter <i class="fa-solid fa-envelope"></i></button></a>
						</div>
						<div class="col-4">
							<br/><a href="comptes.php"><button type="button" class="btn btn-primary btn-block">Retour à la liste des comptes <span class="glyphicon glyphicon-arrow-up"></span></button></a> 
						</div>
						<div class="col-4">
							<form action="deconnection.php" method="post">
								<br/><button class="btn btn-info btn-block" type="submit">Me déconnecter <i class='fa-regular fa-circle-xmark'></i></button>
							</form>
						</div>
					</div>
				</div>
				<div class="col-3">
					<!--- Logo et lien IUT -->
					Réalisé par <br/><a href="http://www.iut-rodez.fr" target="_blank"><img src="../images/LogoIut.png" id="logoIUT" alt="Logo IUT" /></a>
				</div>			
			</div>	
		</div>
		<?php 
			deconnecteBD(); // Appel de la fonction permettant de se deconnecter proprement.
		?>
  </body>
</html>
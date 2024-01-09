<?php
var_dump($_POST);
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	if ($_SESSION['role'] != "Admin") {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: forum.php');
    exit();
}
	// Intégration des fonctions qui seront utilisées pour les acces à la BD
	require('../fonctions/gestionBD.php');
	
	// Connexion à la BD
	if (!connecteBD($erreur)) {
		// Pas de connexion à la BD, renvoie vers l'index
		header('Location: ../index.php');
		exit();
	} 

  if (isset($_POST['nomEntreprise']) && isset($_POST['secteurActivite']) && isset($_POST['presentation'])
  && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp'])){

    //echo "Test debug";
    //htmlspecialchars($identifiantSaisi);

    $nom=htmlspecialchars($_POST['nomEntreprise']);
    echo $_POST['nomEntreprise'];

    $activite=htmlspecialchars($_POST['secteurActivite']);
    echo $_POST['secteurActivite'];

    $presentation=htmlspecialchars($_POST['presentation']);
    echo $_POST['presentation'];

    $ville=htmlspecialchars($_POST['ville']);
    echo $_POST['ville'];
    
    $adresse=htmlspecialchars($_POST['adresse']);
    echo $_POST['adresse'];
    
    $codePostal=htmlspecialchars($_POST['cp']);
    echo $_POST['cp'];
    
    $logo="../images/doxio.jpg";

    if(AjoutEntreprise($connexion,$nom,$activite,$presentation,$ville,$adresse,$codePostal,$logo)){
      header('Location: Forum.php');
    } else {
      echo " l'ajout n'a pas était effectué";
    }
  }


  /* $nomEntreprise="";


	if (isset($_POST['nomEntreprise'])) {
		$nomEntreprise = htmlspecialchars($_POST['nomEntreprise']);
	} */



  /* value="<?php echo htmlspecialchars($nomEntreprise);?>" */

  $imageTest="";
  
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Ajouter Entreprise (ADMIN)</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


		<!-- Lien vers mon CSS -->
		<link href="../monStyle.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>

    <div id="container">
      <header>
        
      </header>
      
      <form class="formAjoutEntreprise" action="ajoutEntreprise.php" method="post">

        <div class="row caseCentrer">
          <div class=" col-md-4 col-sm-3">
            <img  src="../images/Eureka.png">
          </div>
          <div class=" col-md-8 col-sm-9 titreCentrer">
            <h2 class="h2center">AJOUTER UNE ENTREPRISE</h2>
          </div>
        </div>

        <fieldset>
          <legend>Les champs marqué par une * sont obligatoire</legend>


          <div>
            <label for="first-name">Nom de L'entreprise*</label>
            <input type="text" name="nomEntreprise"  required >
            <br/>
            <label for="first-name">Secteur D'activité*</label>
            <input type="text" name="secteurActivite" required>
            <br/>

            <label for="message">Presentation de l'entreprise*</label>
            <br/>
            <textarea rows="5" cols="50" tabindex="-1" name="presentation" required></textarea>

            <p class="TextDescriptif">Localisation*</p>
            <div class="row">
              
              <div class="col-md-5 col-sm-12 col-12">
                <input class="" name="ville" type="text" placeholder="Ville" required>
              </div>

              <div class="col-md-5 col-sm-8 col-8">
                <input class="" type="text" name="adresse" placeholder="Adresse" required>
              </div>

              <div class="col-md-2 col-sm-4 col-4">
                <input class="" type="text" name="cp" placeholder="Code Postal ex: 12000" pattern="[0-9]{5}" required>
              </div>
              
            </div>
            <!-- <label for="image">Importer une image :</label><br>
            <input type="file" name="image"><br><br>    -->
          </div>
          

          <div class="row caseCentrer">
            <div class=" col-md-4 col-sm-3 col-3">
              <a href="Forum.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
            </div>
            <div class=" col-md-8 col-sm-9 col-9 boutonDroite">
              <input name="action" type="hidden" value="ajout">
              <input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Ajouter l'Entreprise">
              
            </div>
          </div>
          <!-- <input type="submit" name="submitButton" value="Ajouter l'Entreprise"> -->
          <!-- <input type="reset" value="Reset all info" tabindex="-1"> -->

        </fieldset>
      </form>
    </div>
    </body>
</html>
<?php
var_dump($_POST);
	session_start();
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
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

  if (isset($_POST['nomEtudiant']) && isset($_POST['prenomEtudiant']) && isset($_POST['username'])
  && isset($_POST['mdp']) && isset($_POST['role']) && isset($_POST['filiere'])){

    //echo "Test debug";
    //htmlspecialchars($identifiantSaisi);

    $nom=htmlspecialchars($_POST['nomEtudiant']);


    $prenom=htmlspecialchars($_POST['prenomEtudiant']);


    $username=htmlspecialchars($_POST['username']);


    $mdp=htmlspecialchars($_POST['mdp']);

    
    $role=htmlspecialchars($_POST['role']);
    include("../services/AdminService.php");
    $role = getRoleId(getPDO(),$role);
    
    $filiere=htmlspecialchars($_POST['filiere']);
    include("../services/FiliereService.php");
    $filiere = getIdFiliere(getPDO(),$filiere);
    
    
    var_dump($role);
    if($role != null) {
        if(AjoutUtilisateur($connexion,$nom,$prenom,$username,$mdp,$role,$filiere)){
            header('Location: Forum.php');
          } else {
            var_dump($role);
            echo " l'ajout n'a pas était effectué";
          }
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
		<title>Connexion Eureka</title>

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
      
      <form class="formAjoutEntreprise" action="ajoutEtudiant.php" method="post">

        <div class="row caseCentrer">
          
          <div class=" col-md-12 col-sm-12 titreCentrer">
            <h2 class="h2center">AJOUTER UN UTILISATEUR</h2>
          </div>
        </div>

        <fieldset>
          <legend>Les champs marqué par une * sont obligatoire</legend>


          <div>
            <label for="first-name">Nom de L'etudiant*</label>
            <input type="text" name="nomEtudiant"  required >
            <br/>
            <label for="first-name">Prenom de L'etudiant*</label>
            <input type="text" name="prenomEtudiant" required>
            <br/>
            <label for="first-name">Username*</label>
            <input type="text" name="username" required>
            <br/>
            <label for="first-name">Mot de Passe*</label>
            <input type="text" name="mdp" required>
            <br/>
            <label for="first-name">Role*</label>
            <input type="text" name="role" required>
            <br/>
            <label for="first-name">Filiere (Si etudiant)*</label>
            <input type="text" name="filiere" required>
            <br/>
            <!-- <label for="image">Importer une image :</label><br>
            <input type="file" name="image"><br><br>    -->
          </div>
          

          <div class="row caseCentrer">
            <div class=" col-md-4 col-sm-3 col-3">
              <a href="Forum.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
            </div>
            <div class=" col-md-8 col-sm-9 col-9 boutonDroite">
              <input name="action" type="hidden" value="ajout">
              <input type="submit" class="btn btn-outline-primary tailleMoyenne " name="bouttonAjout" value="Ajouter Utilisateur">
              
            </div>
          </div>
          <!-- <input type="submit" name="submitButton" value="Ajouter l'Entreprise"> -->
          <!-- <input type="reset" value="Reset all info" tabindex="-1"> -->

        </fieldset>
      </form>
    </div>
    </body>
</html>
<?php
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
  $pdo = getPDO();
  if (isset($_POST['nomEtudiant']) && isset($_POST['prenomEtudiant']) && isset($_POST['username'])
  && isset($_POST['mdp']) && isset($_POST['role']) ){

    //echo "Test debug";
    //htmlspecialchars($identifiantSaisi);

    $nom=htmlspecialchars($_POST['nomEtudiant']);


    $prenom=htmlspecialchars($_POST['prenomEtudiant']);


    $username=htmlspecialchars($_POST['username']);


    $mdp=htmlspecialchars($_POST['mdp']);
    
    
    $role=htmlspecialchars($_POST['role']);
    include("../services/AdminService.php");
    $roleId = getRoleId(getPDO(),$role);
    
    $filiere=htmlspecialchars($_POST['filiere']);
    include("../services/FiliereService.php");
    
    
    $mdpValide = preg_match('/^\S{8,30}$/',$mdp);
    $nomValide = preg_match('/^[a-z]+$/',$nom) && !preg_match('/\s/',$nom);
    $prenomValide = preg_match('/^[a-z]+$/',$prenom)&& !preg_match('/\s/',$nom);
    $usernameValide = filter_var($username, FILTER_VALIDATE_EMAIL);
    if($role != null && $mdpValide && $nomValide && $prenomValide ) {
        if(AjoutUtilisateur($connexion,$nom,$prenom,$username,$mdp,$role,$filiere)){
            header('Location: GestionUtilisateur.php');
          } else {
            echo " l'ajout n'a pas était effectué";
          }
    }
  }
  include("../services/FiliereService.php");

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
      
      <form class="formAjoutEntreprise" action="ajoutUtilisateur.php" method="post">

        <div class="row caseCentrer">
          
          <div class=" col-md-12 col-sm-12 titreCentrer">
            <h2 class="h2center">AJOUTER UN UTILISATEUR</h2>
          </div>
        </div>

        <fieldset>
          <legend>Les champs marqué par une * sont obligatoire</legend>


          <div>
            <label for="first-name">Nom de L'etudiant* (en minuscule) <?php if(isset($nom) && !$nomValide){ echo '<div class="messErreur">le nom ne doit contenir aucune majuscule</div>';}?></label></label>
            <input type="text" name="nomEtudiant" <?php if(isset($nom)){echo 'value="'.$nom.'"';} if(isset($nom) && (!$nomValide)){echo 'class="erreur"';} ?> required >
            <br/>
            <label for="first-name">Prenom de L'etudiant* (en minuscule) <?php if(isset($prenom) && !$prenomValide){ echo '<div class="messErreur">le prenom ne doit contenir aucune majuscule</div>';}?></label>
            <input type="text" name="prenomEtudiant" <?php 
            if(isset($prenom) && (!$prenomValide)){echo 'class="erreur"';} 
            if(isset($prenom)){echo 'value="'.$prenom.'"';}?> required>
            <br/>
            <label for="first-name">Username* (le nom d'utilisateur est l'adresse mail d'un étudiant)
            <?php if(isset($username) && !$usernameValide){ echo '<div class="messErreur">votre nom d\'utilisateur ne respecte pas le format mail</div>';}?>  
            </label>
           
            <input type="text" name="username" <?php if(isset($username) && (!$usernameValide)){echo 'class="erreur"';} if(isset($username)){echo 'value="'.$username.'"';}?> required>
            <br/>

            <label for="first-name">Mot de Passe* (le mot de passe doit être compris entre 8 et 30 caractére sans espace) <?php if(isset($mdp) && (strlen($mdp)<8)){
              echo '<div class="messErreur">le mot de passe doit avoir au minimum 8 caractére</div>';
            }else if(isset($mdp) && (strlen($mdp) >30)){
              echo '<div class="messErreur">le mot de passe doit avoir au maximum 30 caractére</div>';
            }else if(isset($mdpValide) && !$mdpValide){
                echo '<div class="messErreur">le mot de passe ne doit contenir aucun caractére espace</div>';
            }?></label>
            <input type="text"<?php if(isset($mdp) && (!$mdpValide)){echo 'class="erreur"';}?> <?php if(isset($mdp)){echo 'value="'.$mdp.'"';}?> name="mdp" required>
            
            <br/>
            <label for="first-name">Role*</label>
            <select id="role" name="role" class ="form-control" required>
              <?php
              $allRole = displayAllRole();
               foreach($allRole as $role){
                if(isset($_POST["role"]) && $role["id"] == $_POST["role"]){
                     echo '<option selected value='.$role["id"].'>'.$role["designation"].'</option>';
                }else{
                    echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
                }
              }
              ?>
            </select>

            <br/>
            <br>
            <label for="first-name" id="filiere" hidden>Filiere (Si etudiant)*</label>
            <select type="text" id="filiere2" name="filiere" class ="form-control" required hidden>
            <?php $filieres = getFilieres($pdo);
            foreach($filieres as $fil){
              echo '<option value="'.$fil["id"].'">'.$fil["field"].'</option>';
            }
            ?>
            </select>
            <br>
            <br/>
            <!-- <label for="image">Importer une image :</label><br>
            <input type="file" name="image"><br><br>    -->
          </div>
          

          <div class="row caseCentrer">
            <div class=" col-md-4 col-sm-3 col-3">
              <a href="GestionUtilisateur.php" class="btn btn-outline-primary tailleMoyenne">Retour</a>
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
    <script src="../js/ajoutUser.js" ></script>
    </body>
</html>
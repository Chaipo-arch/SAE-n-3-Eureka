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
    if(isset($_POST["modif"])){
        modifyUsers($_POST["username"],$_POST["prenom"],$_POST["nom"],$_POST["password"],$_POST["role"],$_POST["filiere"]);
        $_POST["modif"] == null;
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


	<!-- Lien vers mon CSS -->
	<link href="../css/user.css" rel="stylesheet">

	<!-- Lien vers CSS fontawesome -->
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
</head>
<body>
    
   
    <select name="role" id="roles">
        <option value="0">tous</option>
        <?php 
		$allRole = displayAllRole();
        foreach($allRole as $role){
            echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
        }
        ?>
    </select>
    <input type="hidden" name="role" value="Home">
    
    </form>

    <button id="openModalBtn"><i class="fa-solid fa-plus"></i></button>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeModalBtn">&times;</span>
    <form action="page1.php" method="post">
        username : <input type="text" name="username"><br><br>
        password : <input type="password" name="password"><br><br>
        nom : <input type="text" name="nom"><br><br>
        prenom : <input type="text" name="prenom"><br><br>
        role : <select name="role" id="role">
        <option value="0">aucun</option>
        <?php 
		$allRole=displayAllRole();
        foreach($allRole as $role){
            echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
        }
        ?>
    </select>
    <select name="filiere" id="filiere" hidden>
    <option value="0">tous</option>
        <?php 
		$allFiliere=displayAllFiliere();
        foreach($allFiliere as $filiere){
            echo '<option value='.$filiere["id"].'>'.$filiere["field"].'</option>';
        }?>
    </select>
        <input type="submit" value="Créer l'utilisateur">
        <input type="hidden" name="ajoutUser" value="true">
    </form>
  </div>
</div>

    <?php
		$allUsers = displayAllUsers();
        foreach ($allUsers as $uses){
            echo'<div class="card text-center" id="id'.$uses["id"].'">
            <div class="card-header" id="username">
             '.$uses["Username"].'
            </div>
            <form action="page1.php" method="post">
            <div  class="card-body">
            Nom :  <p class="card-text" name="nom" id="nom'.$uses["id"].'" value="'.$uses["nom"].'">'.$uses["nom"].' </p>
            Prenom : <p class="card-text" name="prenom" id="prenom'.$uses["id"].'">'.$uses["prenom"].'</p>
            Mot de passe : <p class="card-text" name="password" id="password'.$uses["id"].'">'.$uses["password"].'</p>
            <input type="hidden" name="role" id="id" value="'.$uses["id_role"].'">
            <input type="hidden" name="filiere" id="filiere'.$uses["id"].'" value="'.$uses["id_filiere"].'">
            <button type="button" value="modifier" id="modif'.$uses["id"].'"><i class="fa-solid fa-pen"></i></button>
            <input type="submit" id="valideLaModif'.$uses["id"].'" value="modif" name="modif" hidden>
            </div>
            </form>
          </div>
          
          <br>';
           
        }

   
    
if(isset($_POST["ajoutUser"])&& $_POST["ajoutUser"]=="true"){
    addUsers($_POST["username"],$_POST["prenom"],$_POST["nom"],$_POST["password"],$_POST["role"],null);
} 

?>


      <script src="../js/user.js"></script>
</body>
</html>
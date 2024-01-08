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
        modifyUsers($_POST["username"],$_POST["prenom"],$_POST["nom"],$_POST["password"],$_POST["role"],$_POST["filiere"],$_POST['id']);
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
<form action="page1.php" method="post">
    
    <select name="role" id="roles">
        <option value="0">tous</option>
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
        <input type="text" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
        <input type="submit">
    </select>
</form>
   
    <?php  var_dump($_POST)?>
    
    
    

<button id="openModalBtn"><i class="fa-solid fa-plus"></i></button>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeModalBtn">&times;</span>
    
<div class="row">
    <div class="col-4">
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
    <input type="submit" value="Créer l'utilisateur">
    <input type="hidden" name="ajoutUser" value="true">
    <select name="filiere" id="filiere" hidden>
    <option value="0">tous</option>
        <?php 
		$allFiliere=displayAllFiliere();
        foreach($allFiliere as $filiere){
            echo '<option value='.$filiere["id"].'>'.$filiere["field"].'</option>';
        }
        
        ?>
    </select>
       
    </form>
</div>
</div>
<div class="row">
<div class="col-4">
   <form action="page1.php" method="post">
   <input type="file" name="file">
   </form>
  </div>
</div>
</div>
</div>
    <br>
<?php
        
        if(isset($_POST['pagination'])){ 
            $numeroDeLaPage = (int)$_POST['pagination'];
        }else{
            $numeroDeLaPage = 0;
        }
        $cherche = "";
        if(isset($_POST["recherche"]) && isset($_POST["role"]) && $_POST["role"] !=0){
            $cherche = $_POST["recherche"];
            
            $nombre = displayNbLigneWithRole($cherche,$_POST["role"]);
            
        }else if(isset($_POST["recherche"]) ){
            $cherche = $_POST["recherche"];
            $nombre = displayNBLigne($cherche);
        }else{
            $nombre = displayNBLigne($cherche);
        }
        
      
        $nombreDePage = intval($nombre[0]["total_pages"]);
        
    ?>
    <div class="container">
        <div class="row">
           
            <nav aria-label="Page navigation example col-md-12">
                <ul class="pagination justify-content-center">
               
                    <li class="page-item">
                        <form action="page1.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="0" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&laquo;" class="page-link" >
                        </form>
                    </li>
                    <?php if(1 <= $numeroDeLaPage){?>
                    <li class="page-item">
                        <form action="page1.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="<?php echo $numeroDeLaPage - 1 ?>" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&lsaquo;" class="page-link">
                        </form>
                    </li>
                    <?php }?>
                    <?php if(2 <= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="page1.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage - 2?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage - 2?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(1 <= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="page1.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage - 1?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage - 1?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <li class="page-item">
                            <form action="page1.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage?>" class="page-link" disabled>
                            </form>
                        </li>
                        <?php if(($nombreDePage-2) >= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="page1.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage + 1?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage + 1?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(($nombreDePage-3) >= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="page1.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage + 2?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage + 2?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(($nombreDePage-2) >= $numeroDeLaPage){?>
                    <li class="page-item">
                        <form action="page1.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="<?php echo $numeroDeLaPage + 1 ?>" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&rsaquo;" class="page-link">
                        </form>
                    </li>
                    <?php }?>
                    <li class="page-item">
                        <form action="page1.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="<?php echo $nombreDePage-1 ?>" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&raquo;" class="page-link">
                        </form>
                    </li>
                    
                </ul>
            </nav>
        
        </div>
    </div>
    <?php 
            if(isset($_POST["recherche"]) && trim($_POST["recherche"]) && isset($_POST["role"]) && $_POST["role"] != 0) {
                $allUsers = rechercheByRole($_POST["recherche"], $_POST["role"], $numeroDeLaPage);
            }else if (isset($_POST["role"]) && $_POST["role"] != 0){
                $allUsers = rechercheByRole($_POST["recherche"], $_POST["role"], $numeroDeLaPage);
            }
            else if(isset($_POST["recherche"]) && trim($_POST["recherche"])) {
                $allUsers = recherche($_POST["recherche"], $numeroDeLaPage);
            } else {
                $allUsers = displayAllUsers($numeroDeLaPage);
            }
            
		
        foreach ($allUsers as $uses){
            echo'<div class="card text-center" id="id'.$uses["id"].'">
            <div class="card-header" id="username">
             '.$uses["Username"].'
            </div>
            <form action="page1.php" method="post">
            <div  class="card-body">
            Nom d\'utilisateur :  <div class="card-text" name="username" id="username'.$uses["id"].'" value="'.$uses["Username"].'">'.$uses["Username"].' </div><br>
            Nom :  <div class="card-text" name="nom" id="nom'.$uses["id"].'" value="'.$uses["nom"].'">'.$uses["nom"].' </div><br>
            Prenom : <div class="card-text" name="prenom" id="prenom'.$uses["id"].'">'.$uses["prenom"].'</div><br>
            Mot de passe : <div class="card-text" name="password" id="password'.$uses["id"].'">'.$uses["password"].'</div><br>
            
            <input type="hidden" name="id" id="id" value="'.$uses["id"].'">
            <input type="hidden" name="filiere" id="filiere'.$uses["id_filiere"].'" value="'.$uses["id_filiere"].'">
            <select  name="role" disabled id="idRole'.$uses["id_filiere"].'" value="'.$uses["id_role"].'">';
            foreach($allRole as $role){
                if($role["id"] == $uses["id_role"]){
                    echo '<option selected value='.$role["id"].'>'.$role["designation"].'</option>';
                }else{
                    echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
                }
            }
            echo'</select><select disabled name="filiere" id="filiere'.$uses["id_filiere"].'" value="'.$uses["id_filiere"].'">';
            foreach($allFiliere as $filiere){
                if($filiere["id"] == $uses["id_filiere"]){
                    echo '<option selected value='.$filiere["id"].'>'.$filiere["field"].'</option>';
                }else{
                    echo '<option value='.$filiere["id"].'>'.$filiere["field"].'</option>';
                }
                
            }
            echo'</select>
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
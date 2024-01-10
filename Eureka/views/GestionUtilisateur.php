<?php
    
	session_start();
    
	// test si on est bien passé par la page de login sinon on retourne sur index.php
	if (!isset($_SESSION['connecte'])) {
		//Pas de session en cours, on est pas passé par le login password
		header('Location: ../index.php');
		exit();
	}
	if ($_SESSION['role'] == "Etudiant") {
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
    if (isset($_POST['action']) && $_POST['action'] == "supprimerUtilisateur") {
        include("../services/AdminService.php");
        $idUser = htmlspecialchars($_POST['idUser']);
        $role = htmlspecialchars($_POST['roleDelete']);
        if($role == 3) {
            deleteEtudiant(getPDO(), $idUser);
        } else {
            deleteUser(getPDO(), $idUser);
        }
	   
    }
    if(isset($_POST["modif"])){
        modifyUsers($_POST["username"],$_POST["prenom"],$_POST["nom"],$_POST["password"],$_POST["role"],$_POST["filiere"],$_POST['id']);
        $_POST["modif"] == null;
    }
   
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Eureka - Gestion Utilisateur (ADMIN)</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/HeaderCss.css" rel="stylesheet">
    <link href="../css/EntrepriseCss.css" rel="stylesheet">


	<!-- Lien vers mon CSS -->
	<link href="../css/user.css" rel="stylesheet">

	<!-- Lien vers CSS fontawesome -->
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
</head>
<body>
    <?php 
		include("../fonctions/viewHelper.php");
		headerHelper();
		?>
	</br>
		
	<div class="container separation">
		<div class="col-md-12">
            <form action="GestionUtilisateur.php" method="post">
                <div class= "row centre">
					<div class="col-md-3 centre">
                        <select name="role" id="roles" >
                            
                            <?php 
                                if($_SESSION['role'] == 'Admin' ){
                                    echo '<option value="0">Tous</option>';
                                    $allRole = displayAllRole(); 
                                    foreach($allRole as $role){
                                        if (isset($_POST["role"]) && $role["id"] == $_POST["role"] || isset($_SESSION['roleAffichage']) && $_SESSION['roleAffichage'] == $role["id"]){
                                            
                                            if (isset($_SESSION['roleAffichage']) && !isset($_POST["role"])) {
                                                $_POST["role"] = $_SESSION['roleAffichage'] ;
                                                $_POST["recherche"] = "";
                                            } else {
                                                $_SESSION['roleAffichage'] = $_POST["role"];
                                            }
                                             echo '<option selected value='.$role["id"].'>'.$role["designation"].'</option>';
                                        } else {
                                            echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
                                        }
                
                                    }
                                } else {
                                    echo '<option selected value=3>Étudiant</option>';
                                    $allRole =array() ;
                                    $role['id'] = 3;
                                    $role['designation'] = "Étudiant";
                                    $allRole[] = $role;
                                    $_POST['role'] = $role['id'];
                                    
                                    if(!isset($_POST['recherche'])) {
                                        $_POST['recherche'] = "";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 centre">
                        <input type="text submit" class = "form-control" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                    </div>
                    <div class="col-md-2 centre">
                        <input type="submit" value="Rechercher">
                    </div>
                </div>
                    
            </form>
           <?php  if($_SESSION['role'] == 'Admin' ){ ?>
            <div class="col-md-12 centre">
				<form class="form my-1 my-lg-1" action="ajoutUtilisateur.php" method="Post">
					<input class="btn btn-form-control mr-sm-1 btn-outline-dark" type="submit" value="+">
				</form>	
			</div>
            <?php } ?>
        </div>
        
        <?php  if($_SESSION['role'] == 'Admin' ){ ?>
        <div class="row">
            <div class="col-4">
                <form action="GestionUtilisateur.php" method="post">
                    <input type="file" name="file">
                </form>
            </div>

        </div>
        <?php } ?>
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
                        <form action="GestionUtilisateur.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="0" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&laquo;" class="page-link" >
                        </form>
                    </li>
                    <?php if(1 <= $numeroDeLaPage){?>
                    <li class="page-item">
                        <form action="GestionUtilisateur.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="<?php echo $numeroDeLaPage - 1 ?>" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&lsaquo;" class="page-link">
                        </form>
                    </li>
                    <?php }?>
                    <?php if(2 <= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="GestionUtilisateur.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage - 2?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage - 2?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(1 <= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="GestionUtilisateur.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage - 1?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage - 1?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <li class="page-item">
                            <form action="GestionUtilisateur.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage?>" class="page-link pageActu" disabled>
                            </form>
                        </li>
                        <?php if(($nombreDePage-2) >= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="GestionUtilisateur.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage + 1?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage + 1?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(($nombreDePage-3) >= $numeroDeLaPage){?>
                        <li class="page-item">
                            <form action="GestionUtilisateur.php" method="POST">
                                <input name="codeUtilisateur"  hidden>
                                <input name="pagination" value="<?php echo $numeroDeLaPage + 2?>" hidden>
                                <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                                <input type="submit" value="<?php echo $numeroDeLaPage + 2?>" class="page-link">
                            </form>
                        </li>
                        <?php }?>
                        <?php if(($nombreDePage-2) >= $numeroDeLaPage){?>
                    <li class="page-item">
                        <form action="GestionUtilisateur.php" method="POST">
                            <input name="codeUtilisateur"  hidden>
                            <input name="pagination" value="<?php echo $numeroDeLaPage + 1 ?>" hidden>
                            <input type="hidden" name="recherche" <?php if(isset($_POST['recherche'])) { echo 'value="'.$_POST['recherche'].'"'; } ?>>
                            <input type="submit" value="&rsaquo;" class="page-link">
                        </form>
                    </li>
                    <?php }?>
                    <li class="page-item">
                        <form action="GestionUtilisateur.php" method="POST">
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
                
            
                <div  class="card-body">
                    <form action="modifierUtilisateur.php" method="post">
                        Nom d\'utilisateur :  <div class="card-text" name="username" id="username'.$uses["id"].'" value="'.$uses["Username"].'">'.$uses["Username"].' </div><br>
                        Nom :  <div class="card-text" name="nom" id="nom'.$uses["id"].'" value="'.$uses["nom"].'">'.$uses["nom"].' </div><br>
                        Prenom : <div class="card-text" name="prenom" id="prenom'.$uses["id"].'">'.$uses["prenom"].'</div><br>';
                        if($_SESSION['role'] == 'Admin' ){
                            echo 'Mot de passe : <div class="card-text" name="password" id="password'.$uses["id"].'">'.$uses["password"].'</div><br>';
                        }
                        echo '<input type="hidden" name="idEtudiant" id="id" value="'.$uses["id"].'">
                        <input type="hidden" name="filiere" id="filiere'.$uses["id_filiere"].'" value="'.$uses["id_filiere"].'">
                        <select  name="role" disabled id="idRole'.$uses["id_filiere"].'" value="'.$uses["id_role"].'">';
                        foreach($allRole as $role){
                            if($role["id"] == $uses["id_role"]){
                                echo '<option selected value='.$role["id"].'>'.$role["designation"].'</option>';
                            }else{
                                echo '<option value='.$role["id"].'>'.$role["designation"].'</option>';
                            }
                        }
                        echo'</select> ';/*<select disabled name="filiere" id="filiere'.$uses["id_filiere"].'" value="'.$uses["id_filiere"].'">';
                        
                       /*foreach($allFiliere as $filiere){
                            if($filiere["id"] == $uses["id_filiere"]){
                                echo '<option selected value='.$filiere["id"].'>'.$filiere["field"].'</option>';
                            }else{
                                echo '<option value='.$filiere["id"].'>'.$filiere["field"].'</option>';
                            }
                            
                        }
                         echo'</select>*/
                        if($_SESSION['role'] == 'Admin' ){
                        echo' <button type="button submit" value="modifier" id="modif'.$uses["id"].'"><i class="fa-solid fa-pen"></i></button>
                        <input type="submit" id="valideLaModif'.$uses["id"].'" value="modif" name="modif" hidden>';
                        
                   
                        }
                         echo '</form>';?>
          


          <?php if($uses['id_role'] == 3) { ?>
            <form action="Entreprise.php" method="POST">
			    <input name="controller" type="hidden" value="Etudiant">
				<input  name="action" type="hidden" value="afficherSouhait">
				<input type="hidden" name="idUserS" value="<?php echo $uses['id'] ;?>">
                <input type="hidden" name="nomUser" value="<?php echo $uses['nom'] ;?>">
                <input type="hidden" name="prenomUser" value="<?php echo $uses['prenom'] ;?>">
				<input type="submit" value="Voir souhaits">
			</form>
            <?php } 
            if($_SESSION['role'] == 'Admin' ){?>
           <form action="GestionUtilisateur.php" method="post">
                <div class="btn-group mt-2 col-md-12">
                    <input name="idUser" type="hidden" value="<?php echo $uses['id'] ; ?>">
                    <input name="roleDelete" type="hidden" value="<?php echo $uses["id_role"] ; ?>">
                    <input name="action" type="hidden" value="supprimerUtilisateur">
                    <button type="button submit" class="btn btn-sm btn-outline-secondary">
                        Supprimer utilisateur
                    </button>
                                        
                </div>
            </form>
            <?php } ?>
            </br>
          </div>
          </div>
          </br>
        <?php } 
        
if(isset($_POST["ajoutUser"])&& $_POST["ajoutUser"]=="true"){
    addUsers($_POST["username"],$_POST["prenom"],$_POST["nom"],$_POST["password"],$_POST["role"],null);
} 

?>


      <script src="../js/user.js"></script>
    </div>
    
		
			 
        
    
    
    
    


</body>
</html>
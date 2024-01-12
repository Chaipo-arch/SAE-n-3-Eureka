<?php
    
	session_start();
    $_SESSION['AffichageSouhaitEtu']=null;
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
        include("../services/FiliereService.php");
		include("../fonctions/viewHelper.php");
		headerHelper();
		?>
	</br>
		
	<div class="container-fluid separation ">
    <div>
		<div class="col-md-12">
            <form action="GestionUtilisateur.php" method="post">
                <div class= "row centre div-box">
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
                        <input class="btn btn-outline-dark btn-block" type="submit" value="Rechercher">
                    </div>
                </div>
                    
            </form>
            <div>

            
           <?php  if($_SESSION['role'] == 'Admin' ){ ?>
            <div class="container-fluid ">
            <div class="row div-box padding-top">
                <div class="col-md-5 col-sm-5 col-12 d-flex align-items-center justify-content-center">
                    <form class="form my-1 my-lg-1" action="ajoutUtilisateur.php" method="post">
                        <input class="btn btn-outline-dark btn-block" type="submit" value="+ Ajouter un utilisateur">
                        </br>
                    </form>
                </div>
        
                <div class="col-md-7 col-sm-7 col-12 center-input">
                    <form action="GestionUtilisateur.php" method="post" enctype="multipart/form-data">
                        <div>
                            <div>
                                <label for="fichier_csv">Importer des utilisateurs par CSV :</label>
                                <input type="file"  name="fichier_csv" accept=".csv">
                                </br></br>
                            </div>
                            <div>
                                <input class="btn btn-outline-dark btn-block" type="submit" value="Importer">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
    
            <?php } ?>
        </div>
        
        <?php  if($_SESSION['role'] == 'Admin' ){ ?>
        <!-- <div class="row"> -->
        <?php 
        
        /* $filiere = array(); */
        $filiere = getFilieres(getPDO());
        //var_dump($filiere);



        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_FILES["fichier_csv"]["tmp_name"])) {
            $ErreurFormat = False;
            try {
                $nomficTypes = $_FILES['fichier_csv']['tmp_name'];
                $indexLigne = -1;
                // Lecture du contenu du fichier CSV
                $tabAjout = file($nomficTypes, FILE_IGNORE_NEW_LINES);
                //var_dump($tabAjout);
                /* echo $tabAjout[0]; */
                foreach ($tabAjout as $ligne) {
                    // Divise la ligne en utilisant le séparateur ";"
                    $data = explode(';', $ligne);
                    $indexLigne += 1;
                    //ON VERIFIE LA VALIDIT2 DU FORMAT DES CASE DU FICHIER

                    if (filter_var($data[0], FILTER_VALIDATE_EMAIL)) {
                        //echo "L'adresse email est valide / ";
                        if (preg_match('/^[a-zA-ZéèêàâiïIAÉÈÊÀÂ]+$/', $data[1]) && !preg_match('/\s/', $data[1])){
                            //echo "succes nom / ";
                            if (preg_match('/^[a-zA-ZéèêàâiïIAÉÈÊÀÂ]+$/', $data[2]) && !preg_match('/\s/', $data[1])){
                                //echo "succes prenom / ";

                                /* $estDifferent = true;
                                
                                foreach ($filiere as $element) {
                                    if ($estDifferent){
                                        if ($data[3] == $element) {
                                            $estDifferent = false;
                                        }
                                    }   
                                } */

                                //TODO condition a modifier si il est possible de rajouter des filliéres (prendre la taille de la bd)
                                if (preg_match('/^[1-5]+$/', $data[3])){
                                    //echo "succes filiere / ";
                                    if(preg_match('/^\S{8,30}$/', $data[4])){
                                        //echo "succes mdp";
                                    }else{
                                        //echo "Erreur mdp";
                                        $ErreurFormat = True;
                                        break;
                                    }
                                }else{
                                    //echo "Erreur filiere";
                                    $ErreurFormat = True;
                                    break;
                                }

                                /* if ($estDifferent) {
                                    echo "Erreur filiere";
                                    $ErreurFormat = True;
                                    break;
                                } else {
                                    echo "succes filiere / ";
                                    if(preg_match('/^\S{8,30}$/', $data[4])){
                                        echo "succes mdp";
                                    }else{
                                        echo "Erreur mdp";
                                        $ErreurFormat = True;
                                        break;
                                    }
                                } */

                            }else{
                                $ErreurFormat = True;
                                //echo "Erreur prenom";
                                break;
                            }
                        }else{
                            $ErreurFormat = True;
                            //echo "Erreur Nom";
                            break;
                        }
                    } else {
                        $ErreurFormat = True;
                        
                        break;
                    }

                    //echo "<br>"; // Ajoute un saut de ligne pour chaque ligne du fichier CSV
                }

                //var_dump($ErreurFormat);
                
                if ($ErreurFormat==False){
                    //var_dump(getPDO());
                    //var_dump($data);
                    echo "insertion possible !";
                    
                    foreach ($tabAjout as $ligne) { 
                        $data = explode(';', $ligne);
                        AjoutUtilisateurCSV(getPDO(),$data[1],$data[2], $data[0],$data[4],3,$data[3]);
                    }
                    echo "toto";
                    $_SERVER["REQUEST_METHOD"] = NULL;
                    //$_FILES["fichier_csv"] = NULL;
                    //$_FILES["fichier_csv"]["tmp_name"]=NULL;
                    /* $_FILES = NULL;
                    unset($_FILES);
                    $fichier_tmp = "";
                    $is_post_request = false; */
                }else{
                    echo "Le format Incorect";
                    echo "erreur à la ligne :".$indexLigne;
                }
        

            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "Erreur : Aucun fichier n'a été sélectionné.";
        }
        }
        
		?>

        
            

        <!-- </div> -->
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
            
            $i=0;
        foreach ($allUsers as $uses){
            
            $i++;
            echo'<div class="card text-center" id="id'.$uses["id"].'">
                <div class="card-header" id="username">
                '.$uses["Username"].'
                </div>
                
            
                <div  class="card-body">
                    <form action="modifierUtilisateur.php" method="post">
                        Nom d\'utilisateur :  '.$uses["Username"].' <br>
                        Nom :  '.$uses["nom"].'<br>
                        Prenom : '.$uses["prenom"].'<br>';
                        if($_SESSION['role'] == 'Admin' ){
                            echo 'Mot de passe : '.$uses["password"].'<br>';
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
            
            <div class="btn-group mt-2 col-md-12">
                <button type="button" <?php echo'id="supprimerUtilisateur'.$i.'"'?> class="btn btn-sm btn-outline-secondary">
                        Supprimer utilisateur
                    </button>
            </div>

            <div class="btn-group mt-2 col-md-12">
            <div <?php echo 'id="myModal'.$i.'"'; ?> class="modal ">
            <div class="modal-content">
                voulez vous vraiment supprimer cet utilisateur ?
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" <?php echo 'id="annuler'.$i.'"'?>>Annuler</button>
                <form action="GestionUtilisateur.php" method="post">
                
                <button type="button submit" class="btn btn-danger" id="confirmerSuppressionBtn">Supprimer</button>
                    <input name="idUser" type="hidden" value="<?php echo $uses['id'] ; ?>">
                    <input name="roleDelete" type="hidden" value="<?php echo $uses["id_role"] ; ?>">
                    <input name="action" type="hidden" value="supprimerUtilisateur">
                    
                    </div>
                </form>
            </div>
            </div>
            </div>

         
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


      <script src="../js/gestionUser.js"></script>
    </div>
</body>
</html>
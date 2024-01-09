<?php
$problemeDonnees = false;
$tentativeConnection = false;
require('fonctions/gestionBD.php');
	
// Connexion à la BD
if (!connecteBD($erreur)) {
    $problemeDonnees = true;
} 
session_start();
include("services/UserService.php");
if (isset($_SESSION['connecte']) && $_SESSION['connecte'] && !$problemeDonnees) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: views/forum.php');
    exit();
}

$pdo = getPDO();
if (isset($_POST['identifiant']) && isset($_POST['pwd']) && !$problemeDonnees) {
    $identifiant = htmlspecialchars($_POST['identifiant']);
    $pwd = htmlspecialchars($_POST['pwd']);
    if(!verifUtilisateur($identifiant,$pwd)) {
        $tentativeConnection = true;
    } // TODO ajouter try catch
}
if(isset($_SESSION['connecte']) && $_SESSION['connecte'] && !$problemeDonnees)  {
    header('Location: views/forum.php');
    exit();

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion Eureka</title>

    <!-- Bootstrap CSS -->
    <link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">


    <!-- Lien vers mon CSS -->
    <link href="css/monStyle.css" rel="stylesheet">

    <!-- Lien vers CSS fontawesome -->
    <link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
</head>

<body>

<div class="login-box">
    <h2>Login</h2>
    <?php 
        if ($problemeDonnees) {
    ?>
    <div class="row">
        <div class="col-12">
            <?php echo $messageRetour; ?>
        </div>
    </div>
    <?php 
        } else {
    ?>
    <form action="index.php" method="post">
    <input type="hidden" name="action" value="tentativeConnexion">
        <?php 
            if ($tentativeConnection) { ?>
            <!-- On a essayé de se connecter mais cela a échoué, affichage d'un message d'erreur -->
                <div class="row">
                    <div class="col-12">
                        <p class="enRouge">Identifiant ou mot de passe incorrect</p>
                    </div>
                </div>
        <?php
            }
        ?>
        
        <div class="user-box">
            <input type="text" name="identifiant" required="" autocomplete="off" value="<?php if(isset($_POST['identifiant'])) {echo htmlspecialchars($_POST['identifiant']); }?>">
            <label>Username</label>
        </div>

        <div class="user-box">
            <input type="password" name="pwd" required="">
            <label>Password</label>
        </div>
        
        <div class="col-12">
            <input class="btn btn-info" type="submit" value="Me connecter">
        </div>
    </form>

    
    <?php
        } 
    ?>	
</div>

</body>
</html>
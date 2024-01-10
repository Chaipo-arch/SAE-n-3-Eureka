<?php
session_start();
include("services/UserService.php");
if (isset($_SESSION['connecte']) && $_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: views/forum.php');
    exit();
}

$problemeDonnees = false;
$tentativeConnection = false;
    $host = 'localhost';
    $port = '3306';
    $db = 'eureka';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ];

$pdo = new PDO($dsn, $user, $pass, $options);
if (isset($_POST['identifiant']) && isset($_POST['pwd'])) {
    $identifiant = htmlspecialchars($_POST['identifiant']);
    $pwd = htmlspecialchars($_POST['pwd']);
    if(!verifUtilisateur($pdo,$identifiant,$pwd)) {
        $tentativeConnection = true;
    } // TODO ajouter try catch
}
if(isset($_SESSION['connecte']) && $_SESSION['connecte']) {
    header('Location: views/Entreprise.php');
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
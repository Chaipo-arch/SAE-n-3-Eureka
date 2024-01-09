<?php
session_start();
include("../services/AdminService.php");
include("../services/ForumService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php?action=renvoi');
  exit();
}

if ($_SESSION['role'] != "Admin") {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: forum.php');
    exit();
}
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
if(isset($_POST['edition'])) {
    ModifierForumCaracteristiques($pdo,$_POST['date'],$_POST['dateLimite'],$_POST['duree']);
}
$caracteristiques = getForumCaracteristiques($pdo);
$date = $caracteristiques['date'];
$duree = $caracteristiques['duree'];
$dateLimite = $caracteristiques['dateLimite'];

?>
<html>
<head>
	<title>Eureka - Forum (ADMIN)</title>
	<meta charset ="utf-8"/>
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
    <link href="../css/EntrepriseCss.css" rel="stylesheet">
	<link href="../css/HeaderCss.css" rel="stylesheet">
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("../fonctions/viewHelper.php");
   headerHelper();
  ?>
  <div class="container separation">
        <div class="row">
            <form action="GestionUtilisateur.php" method="post">
                <div class ="col-md-4 centre">
                    <input type="hidden" name="controller" value="Admin">
                    <input type="hidden" name="action" value="modifierForum">
                    <input type="hidden" name="edition" value="true">
                    Date</br>
                    <input type="text" name="date" value="<?php if(isset($date)) { echo $date;}?>">
                
                </div>
                <div class ="col-md-4 centre">
                    Durée par défaut (minutes)</br>
                    <input type="text" name="duree" value="<?php if(isset($duree)) { echo $duree;}?>">
                </div>
                <div class ="col-md-4 centre">
                    Date Limite (rendez Vous)</br>
                    <input type="text" name="dateLimite" value="<?php if(isset($dateLimite)) { echo $dateLimite;}?>">
                </div>
                </br>
                <div class ="col-md-12 centre">
                    </br>
                <input type="submit"  value="Modifier Caractéristiques forum">
                </div>
            </form>
            <div class ="col-md-4 centre">
                <form action="GestionUtilisateur.php" method="post">
                    <input type="submit"  value="Gérer utilisateurs">
                </form>
            </div>
        </div>
        
    
    <a  href="forum.php">Retour</a>
</div>
    <?php footerHelper(); ?>
	
</body>
</html>
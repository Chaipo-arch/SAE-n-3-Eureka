<?php
session_start();
var_dump($_SESSION);
var_dump($_SESSION);
include("../services/UserService.php");
include("../services/ForumService.php");
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
  //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
  header('Location: ../index.php');
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
if($_SESSION['role'] == 'Etudiant')  {
  getSouhait($pdo,$_SESSION['IdUser']);
}

?>
<html>
<head>
	<title>Eureka - Forum</title>
  <link rel="icon" href="../eurekaLogoi.ico" />
  <link rel="icon" href="../eurekaLogo.jpg" />
	<meta charset ="utf-8"/>
	<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="../css/HeaderCss.css" rel="stylesheet">
  <link href="../css/EntrepriseCss.css" rel="stylesheet">
	<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("../fonctions/viewHelper.php");
   headerHelper();
  ?>
  
   <div class="container separation">
      <div class="col-md-12">
        <?php 
        if(isset($pageNTrouve)) {
          ?>
          La page que vous avez demandée n'a pas était trouvé 
        <?php } else {
          $caracteristiques = getForumCaracteristiques($pdo);?>
          <div class="row">
            <div class ="col-md-4 centre">
                
                Date</br>
                <?php echo $caracteristiques['date'] ; ?> 
                
            
            </div>
            <div class ="col-md-4 centre">
                Durée par défaut (minutes)</br>
                <?php echo $caracteristiques['duree'] ; ?> 
                
            </div>
            <div class ="col-md-4 centre">
                Date Limite (rendez Vous)</br>
                <?php echo $caracteristiques['dateLimite'] ; ?> 
            </div>
            </br>
        </div>
          <?php }?>
        </div>
  </div>
    <?php footerHelper(); ?>
	
</body>
</html>
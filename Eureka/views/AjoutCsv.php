<?php
session_start();
include("../services/AdminService.php");
include("../services/EntrepriseService.php");
include("../services/EtudiantService.php");
include("../services/FiliereService.php");
//include("../fonctions/gestionBD.php");
require('../fonctions/gestionBD.php');
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
//TODO rajouter un Get PDO
?>

<?php
            
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Entreprises</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
		<!-- <link href="../css/HeaderCss.css" rel="stylesheet"> -->
		<link href="../css/monStyle.css" rel="stylesheet">
 		<link href="../monStyle.css" rel="stylesheet">
		<link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
	</head>

	<body>
		<?php 
        
		include("../fonctions/viewHelper.php");
		headerHelper();
        /* $filiere = array(); */
        $filiere = getFilieres($pdo);
        //var_dump($filiere);



        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_FILES["fichier_csv"]["tmp_name"])) {
            $ErreurFormat = False;
            try {
                $nomficTypes = $_FILES['fichier_csv']['tmp_name'];
                $indexLigne = -1;
                // Lecture du contenu du fichier CSV
                $tabAjout = file($nomficTypes, FILE_IGNORE_NEW_LINES);
                var_dump($tabAjout);
                /* echo $tabAjout[0]; */
                foreach ($tabAjout as $ligne) {
                    // Divise la ligne en utilisant le séparateur ";"
                    $data = explode(';', $ligne);
                    $indexLigne += 1;
                    //ON VERIFIE LA VALIDIT2 DU FORMAT DES CASE DU FICHIER

                    if (filter_var($data[0], FILTER_VALIDATE_EMAIL)) {
                        echo "L'adresse email est valide / ";
                        if (preg_match('/^[a-z]+$/', $data[1]) && !preg_match('/\s/', $data[1])){
                            echo "succes nom / ";
                            if (preg_match('/^[a-z]+$/', $data[2]) && !preg_match('/\s/', $data[1])){
                                echo "succes prenom / ";

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
                                    echo "succes filiere / ";
                                    if(preg_match('/^\S{8,30}$/', $data[4])){
                                        echo "succes mdp";
                                    }else{
                                        echo "Erreur mdp";
                                        $ErreurFormat = True;
                                        break;
                                    }
                                }else{
                                    echo "Erreur filiere";
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
                                echo "Erreur prenom";
                                break;
                            }
                        }else{
                            $ErreurFormat = True;
                            echo "Erreur Nom";
                            break;
                        }
                    } else {
                        $ErreurFormat = True;
                        
                        break;
                    }

                    echo "<br>"; // Ajoute un saut de ligne pour chaque ligne du fichier CSV
                }

                var_dump($ErreurFormat);
                
                if ($ErreurFormat==False){
                    var_dump($pdo);
                    var_dump($data);
                    echo "insertion possible !";
                    
                    foreach ($tabAjout as $ligne) { 
                        $data = explode(';', $ligne);
                        AjoutUtilisateurCSV($pdo,$data[1],$data[2], $data[0],$data[4],3,$data[3]);
                    }
                    $fichier_tmp = "";
                    $is_post_request = false;
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
		</br>
		
		<div>
        <form action="AjoutCsv.php" method="post" enctype="multipart/form-data">   <!--  -->
            <input type="file" name="fichier_csv" accept=".csv">
            <input type="submit" value="Importer">
        </form>

        </div>


        
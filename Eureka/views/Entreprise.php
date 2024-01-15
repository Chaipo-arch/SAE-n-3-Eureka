<?php
// Démarrage de la session
session_start();

// Inclusion des services nécessaires
include("../services/AdminService.php");
include("../services/EntrepriseService.php");
include("../services/EtudiantService.php");
include("../services/FiliereService.php");
include("../services/UserService.php");

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    // Si non connecté, redirection vers la page d'accueil
    header('Location: ../index.php');
    exit();
}

// Inclusion du fichier de gestion de la base de données
require('../fonctions/gestionBD.php');

// Connexion à la base de données
if (!connecteBD($erreur)) {
    // En cas d'échec de connexion, redirection vers la page d'accueil
    header('Location: ../index.php');
    exit();
}

// Récupération de l'objet PDO
$pdo = getPDO();

// Vérification de la date limite dépassée
$dateLDeppasse = dateLimiteDepassee($pdo);

// Suppression d'une entreprise par l'administrateur
if (isset($_POST['action']) && $_POST['action'] == "supprimerEntreprise" && $_SESSION['role'] == "Admin") {
    $idE = $_POST['idEntreprise'];
    deleteEntreprise($pdo, $idE);
}

// Suppression du souhait d'un étudiant pour une entreprise
if (isset($_GET['action']) && $_GET['action'] == "deleteSouhaitEtudiant") {
    $idE = $_GET['idEntreprise'];
    deleteSouhait($pdo, $idE, $_SESSION['IdUser']);
    getSouhait($pdo, $_SESSION['IdUser']);
}

// Ajout du souhait d'un étudiant pour une entreprise
if (isset($_GET['action']) && $_GET['action'] == "setSouhaitEtudiant") {
    $idE = $_GET['idEntreprise'];
    createSouhait($pdo, $idE, $_SESSION['IdUser']);
    getSouhait($pdo, $_SESSION['IdUser']);
}

// Récupération des filières en fonction du rôle de l'utilisateur
if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
    $filieres = getFilieres($pdo);

    // Initialisation de la filière si elle n'est pas définie
    if (!isset($_SESSION['filiere'])) {
        $_SESSION['filiere'] = 'Toutes';
    } else if (isset($_GET['filiere'])) {
        $_SESSION['filiere'] = $_GET['filiere'];
    }
} else {
    // Récupération de la filière de l'étudiant
    $id = $_SESSION['IdUser'];
    $filieres = getStudentFiliere($pdo, $id);
    $_SESSION['filiere'] = $filieres;
}

// Initialisation de la variable de recherche
if (!isset($_GET['recherche'])) {
    $saisies = '';
} else {
    $saisies = $_GET['recherche'];
}

// Vérification du cas : affichage des souhaits d'un étudiant ou des entreprises dans le cadre d'une recherche
if (isset($_POST['action']) && $_POST['action'] == "afficherSouhait" || (isset($_GET['retour']) && isset($_SESSION['AffichageSouhaitEtu']))) {

    // Récupération de l'ID de l'utilisateur
    if (isset($_SESSION['AffichageSouhaitEtu'])) {
        $idUser = $_SESSION['AffichageSouhaitEtu']['id'];
    } else {
        $idUser = $_POST['idUserS'];
    }

    // Initialisation des données de session pour l'affichage des souhaits
    if (!isset($_SESSION['AffichageSouhaitEtu'])) {
        $_SESSION['AffichageSouhaitEtu']['id'] = $idUser;
        $_SESSION['AffichageSouhaitEtu']['nom'] = $_POST['nomUser'];
        $_SESSION['AffichageSouhaitEtu']['prenom'] = $_POST['prenomUser'];
    }

    // Récupération des entreprises liées au souhait de l'étudiant
    $entreprises = getSouhaitEtudiantEntier($pdo, $idUser);
} else {
    // Initialisation des données de session pour l'affichage des souhaits à null
    $_SESSION['AffichageSouhaitEtu'] = null;

    // Recherche des entreprises en fonction des critères
    $entreprises = rechercheEntreprise($pdo, $saisies, $_SESSION['filiere']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Eureka - Entreprises</title>

    <!-- Bootstrap CSS -->
    <link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
    <link href="../css/HeaderCss.css" rel="stylesheet">
    <link href="../css/EntrepriseCss.css" rel="stylesheet">
    <link href="../fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
    <link href="../css/user.css" rel="stylesheet">
</head>

<body>
<?php
include("../fonctions/viewHelper.php");
headerHelper();
?>
<br>

<div class="container separation">

    <div class="col-md-12 marge-header">
        <?php if (!isset($_SESSION['AffichageSouhaitEtu'])) { ?>
            <div class="col-md-12 centre">
                <h2><?php echo '('.$_SESSION['role'].')'; ?></br>Entreprise Disponible<h2></br>
            </div>
            <form action="Entreprise.php" method="get">
                <div class="row centre div-box-entreprise">
                    <div class="col-md-3 centre padding-top">
                        <select name="filiere" type="submit">
                            <?php if ($_SESSION['role'] != "Etudiant") { ?>
                                <option>Toutes</option>
                            <?php } ?>
                            <!-- option-->

                            <?php
                            if (isset($filieres)) {
                                foreach ($filieres as $filiere) { ?>
									<option
                            <?php if ($_SESSION['filiere'] == $filiere['field']) {
                                echo " selected ";
                            } ?>
                        >
                            <?php echo $filiere['field']; ?>
                        </option>
                    <?php }
                    if ($_SESSION['role'] == "Etudiant") {
                        echo '<option>' . $filieres . '</option>';
                    }
                } ?>
                        </select>
                    </div>

                    <div class="col-md-6 centre padding-top">
                        <input class="form-control mr-sm-1" name="recherche" type="text submit" placeholder="Search"
                               aria-label="Search" value="<?php echo $saisies; ?>">
                    </div>
                    <div class="col-md-2 centre padding-top ">
                        <input type="submit" class="btn btn-outline-dark btn-block" value="Rechercher">
                    </div>
                </div>
            </form>
        <?php } else {
            echo "<div class='col-12 centre'><h1>Souhaits de " . $_SESSION['AffichageSouhaitEtu']['nom'] . " " . $_SESSION['AffichageSouhaitEtu']['prenom'] . ": </h1></div>";
        } ?>
        <!-- affichage de l'ajout d'entreprise si admin !-->
        <?php if ($_SESSION['role'] == 'Admin') { ?>
            <div class="col-md-12 centre padding-entrerpiseAjouter">
                <form class="form my-1 my-lg-1" action="ajoutEntreprise.php" method="Post">
                    <input class="btn btn-form-control mr-sm-1 btn-outline-dark" type="submit"
                           value="+ Ajouter une Entreprise">
                </form>
            </div>
        <?php } ?>
    </div>

    <?php
    if (isset($entreprises) && !empty($entreprises)) {
        foreach ($entreprises as $entreprise) {
            ?>
            <div class="col-12">
                <div class="card mb-4 shadow-sm d-flex flex-row ">
                    <img class="imageEntreprise img-fluid" src="../images/logoEntrepriseTemporaire.jpg"> <?php //echo $entreprise['logo'] ; ?>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <p class="card-text">
                            Nom entreprise :
                            <?php echo $entreprise['Designation']; ?>
                        </p>
                        Presentation :
                        <?php echo $entreprise['presentation']; ?>
                        <div class="btn-group mt-2">
                            <?php if ($_SESSION['role'] == "Etudiant" && $dateLDeppasse) {
                                $passage = false;
                                foreach ($_SESSION['souhait'] as $souhait) {
                                    if ($souhait == $entreprise['id']) {
                                        $passage = true; ?>
                                        <form action="Entreprise.php" methode="get">
                                            <input name="action" type="hidden" value="deleteSouhaitEtudiant">
                                            <input name="idEntreprise" type="hidden" value="<?php echo $entreprise['interId']; ?>">
                                            <button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
                                                Annuler le souhait
                                            </button>
                                        </form>
                                    <?php }
                                }
                                if (!$passage) { ?>
                                    <form action="Entreprise.php" methode="get">
                                        <input name="action" type="hidden" value="setSouhaitEtudiant">
                                        <input name="idEntreprise" type="hidden" value="<?php echo $entreprise['interId']; ?>">
                                        <input name="controller" type="hidden" value="Entreprise">

                                        <button type="button submit" class="btn btn-sm btn-outline-secondary ml-1">
                                            Ajouter souhait
                                        </button>
                                    </form>
                                <?php }
                            } ?>
                        </div>
                        <div class="row">
                            <?php if ($_SESSION['role'] == "Admin") { ?>
                                <form action="modifierEntreprise.php" method="post">
                                    <div class="btn-group mt-2 col-md-12">
                                        <input name="idEntreprise" type="hidden" value="<?php echo $entreprise['id']; ?>">
                                        <button type="button submit" class="btn btn-sm btn-outline-secondary ">
                                            Modifier Entreprise
                                        </button>
                                    </div>
                                </form>

                                <div class="btn-group mt-2 col-md-12">
                                    <?php $i = $entreprise['id']; ?>
                                    <button type="button" <?php echo 'id="supprimerEntreprise' . $i . '"'; ?>
                                            class="btn btn-sm btn-outline-danger ">
                                        Supprimer Entreprise
                                    </button>
                                </div>

                                <div class="btn-group mt-2 col-md-12">
                                    <div <?php echo 'id="myModal' . $i . '"'; ?> class="modal ">
                                        <div class="modal-content">
											Voulez-vous vraiment supprimer cette entreprise ?
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        <?php echo 'id="annuler' . $i . '"' ?>>Annuler
                                                </button>
                                                <form action="Entreprise.php?retour=true" method="post">
                                                    <button type="button submit" class="btn btn-danger"
                                                            id="confirmerSuppressionBtn">Supprimer
                                                        <input name="idEntreprise" type="hidden"
                                                               value="<?php echo $entreprise['id']; ?>">
                                                        <input name="action" type="hidden" value="supprimerEntreprise">
                                                        <input name="action" type="hidden" value="supprimerEntreprise">
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        echo "</br><div class='col-12 centre'><h2>Aucune Entreprise </h2></div>";
    } ?>
</div>

<?php footerHelper(); ?>
<script src="../js/supprimerEntreprise.js"></script>
</body>
</html>
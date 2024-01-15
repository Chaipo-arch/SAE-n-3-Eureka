<?php 
	/**
	 * Aide pour afficher le haut de page 
	 * évite de répéter le code
	 */
	function headerHelper(){
        ?>
        <header>
		<link href="../css/user.css" rel="stylesheet">

		<?php
		// Définir la variable $currentPage en fonction de la page actuelle
		// Supposons que vous puissiez obtenir le nom du script en cours (nom de fichier) avec $_SERVER['PHP_SELF']

		$currentPage = basename($_SERVER['PHP_SELF']);
		?>

		<nav class="navbar">
			<img class="" src="../eurekaLogo.jpg" id="imageHead" alt="logo eureka">
			<?php if($_SESSION['role'] == 'Admin') { ?>
				<a class="nav-link onglet <?php if($currentPage == 'forumA.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="forumA.php">Forum</a>
			<?php } else { ?>
				<a class="nav-link onglet <?php if($currentPage == 'forum.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="forum.php">Forum</a>
			<?php } ?>
			<a class="nav-link onglet <?php if($currentPage == 'Entreprise.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="Entreprise.php?">Entreprises</a>
			<?php if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') { ?>
				<a class="nav-link onglet <?php if($currentPage == 'GestionUtilisateur.php') echo 'active-link'; ?>" href="GestionUtilisateur.php">Gestion Utilisateur</a>
			<?php } else { ?>
				<a class="nav-link onglet <?php if($currentPage == 'souhait.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="souhait.php?controller=Souhait">Mes souhaits</a>
			<?php } ?>
			<?php if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire'){?>
				<a class="nav-link onglet <?php if($currentPage == 'planning.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="planning.php">Planning Etudiants</a>
			<?php } else { ?>
				<a class="nav-link onglet <?php if($currentPage == 'planning.php') echo 'active-link'; ?> d-flex justify-content-center flex-grow-1" href="planning.php" disabled>Mon Planning</a>
			<?php } ?>
			<a class="nav-link onglet d-flex justify-content-center ml-auto deco" id="deco">Déconnexion</a>
		</nav>

			
	</header>
	<div id="myModals" class="modal">
            <div class="modal-content">
                voulez vous vraimment vous déconnecter ?
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="annul">Annuler</button>
                <form action="../views/deconnexion.php" method="post">
                
                <button type="button submit" class="btn btn-danger" id="confirmerSuppressionBtn">Déconnexion</button>
                
                    <input name="action" type="hidden" value="supprimerUtilisateur">
                    
                    </div>
                </form>
            </div>
						</div>
						<script src="../js/header.js"></script>
    <?php 
	}

	/**
	 * Aide pour afficher le bas de page 
	 * évite de répéter le code
	 */
    function footerHelper(){
        ?>
        <!-- <footer>
		<div class="container-fluid">
			<div class="col-md-12">
				a
				<div class= "row">
				</div>
			</div>
		</div>
	</footer> -->
    <?php
    }
?>
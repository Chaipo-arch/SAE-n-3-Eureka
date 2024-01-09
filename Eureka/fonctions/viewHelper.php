<?php 
	/**
	 * Aide pour afficher le haut de page 
	 * évite de répéter le code
	 */
	function headerHelper(){
        ?>
        <header>
		<div class="container-fluid">
			<nav class="navbar navbar-light bg-light ">
				<div class="col-md-12 col-12">
					<div class= "row">
						<div class= "col-md-2 col-4 d-flex justify-content-center ">
							<img src="../eurekaLogo.jpg" id="imageHead" alt="logo eureka">
						</div>
						<div class= "col-md-2 col-8 d-flex justify-content-center">
						<?php if($_SESSION['role'] == 'Admin') { ?>
							<a class="nav-link active onglet rounded" href="forumA.php">Forum</a>
						<?php } else { ?>
							<a class="nav-link active onglet rounded" href="forum.php">Forum</a>
						<?php } ?>
						</div>
						<div class= "col-md-2 col-3 col-xs-6 d-flex justify-content-center ">
							<a class="nav-link active onglet rounded" href="Entreprise.php?">Entreprises</a>
						</div>
						<?php if($_SESSION['role'] == 'Admin' ||$_SESSION['role'] == 'Gestionnaire') { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="GestionUtilisateur.php">souhaits Etudiants</a>
							</div>
						<?php } else { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="souhait.php?controller=Souhait ">Mes souhaits</a>
							</div>
						<?php } ?>
						<?php if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire'){?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Planning">Planning Etudiants</a>
							</div>
						<?php } else { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Planning" disabled>Mon Planning</a>
							</div>
						<?php } ?>
						<div class= "col-md-2 col-3  col-xs-6  d-flex justify-content-center">
							<a class="nav-link active onglet rounded" href="../views/deconnexion.php">Déconnexion</a>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</header>
    <?php 
	}

	/**
	 * Aide pour afficher le bas de page 
	 * évite de répéter le code
	 */
    function footerHelper(){
        ?>
        <footer>
		<div class="container-fluid">
			<div class="col-md-12">
				a
				<div class= "row">
				</div>
			</div>
		</div>
	</footer>
    <?php
    }
?>
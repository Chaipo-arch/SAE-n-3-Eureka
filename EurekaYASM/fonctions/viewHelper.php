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
							<img src="eurekaLogo.jpg" id="imageHead" alt="logo eureka">
						</div>
						<div class= "col-md-2 col-8 d-flex justify-content-center">
						<form class="form my-md-2 my-1" action="Recherche.php" method="get">
							<input class="form-control mr-sm-1" name="recherche" type="search submit" placeholder="Search" aria-label="Search">
						</form>
						</div>
						<div class= "col-md-2 col-3 col-xs-6 d-flex justify-content-center ">
							<a class="nav-link active onglet rounded" href="index.php?controller=Entreprise">Entreprises</a>
						</div>
						<?php if($_SESSION['role'] == 'Admin' ||$_SESSION['role'] == 'Gestionnaire') { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Etudiant">souhaits Etudiants</a>
							</div>
						<?php } else { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Souhait ">Mes souhaits</a>
							</div>
						<?php } ?>
						<?php if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire'){?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Planning">Planning Etudiants</a>
							</div>
						<?php } else { ?>
							<div class= "col-md-2 col-3 col-xs-6  d-flex justify-content-center">
								<a class="nav-link active onglet rounded" href="index.php?controller=Planning">Mon Planning</a>
							</div>
						<?php } ?>
						<div class= "col-md-2 col-3  col-xs-6  d-flex justify-content-center">
							<a class="nav-link active onglet rounded" href="index.php?action=deconnexion">Déconnexion</a>
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
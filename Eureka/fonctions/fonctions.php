<?php 

	function verifUtilisateur($leLogin, $lePassword, & $leMessage){
		// Fonction permettant de vérifier si le login et le PWD existent et sont OK
		// Retourne true si OK sinon false
		$nomficLogins="FichiersDonnees/Logins.csv" ;	// Nom du fichier qui contient les logins / password
		$connecte=false;
		$tabLogins=""; 	// Tableau qui contiendra les logins / password
		
		/* Lecture du fichier des ecritures et remplissage d'un tableau */
		try{ // Bloc try si le fichier n'existe pas 
			if (!file_exists($nomficLogins) ) {
				throw new Exception('Fichier '.$nomficLogins.' non trouvé.');
			}
			// Lecture du fichier dans un tableau
			$tabLogins = file($nomficLogins,FILE_IGNORE_NEW_LINES);
			
			// Test de chaque ligne pour voir si un identifiant corespond 
			foreach($tabLogins as $ligne) {
				$tab = explode(';', $ligne);	// Découpage de la variable dans un tableau
				$login=$tab[0];	
				$pwd=$tab[1];
				if ($leLogin==$login and $lePassword==$pwd) {
					// Un des logins est ok
					$connecte=true;					// On est bien connecté
					$NomClient=$tab[2];				// récup du nom du client dans le tableau
					$_SESSION['connecte']= true ; 	// Stockage dans les variables de session que l'on est connecté (sera utilisé sur les autres pages)
					$_SESSION['nomClient']= $NomClient ;  // Stockage dans les variables de session du nom du client
				}
			}
			
		} catch ( Exception $e ) {
			// Gestion de l'exception levée (fichier inexistant)
			$leMessage="Problème serveur, authentification impossible actuellement. <br>Merci de ré-essayez ultérieurement."; 
			$connecte=false;
		} 
		return $connecte;
	}


?>
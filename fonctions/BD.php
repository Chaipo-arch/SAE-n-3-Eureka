<?php 
	function verifUtilisateur($login,$pwd) {
		// Vérifie si l'utilisateur existe
		// Les paramétres sont le login et le mot de passe envoyé par le formulaire de connexion
		// Renvoie vrai ou faux en fonction si l'utilisateur a été trouvé.
		// Si vrai, enregistrement du role de l'utilisateur, utiliser par la suite pour utiliser des fonctionnalités différentes
		
		global $connexion; 
		try{ // Bloc try bd injoignable 
			$connecte=false;
			$maRequete = $connexion->prepare("SELECT login,pwd,role.id as role FROM clients INNER JOIN role ON id_role = role.id WHERE login = :leLogin and pwd = :lePWD");
			$maRequete->bindParam(':leLogin', $login);
			$maRequete->bindParam(':lePWD', $pwd);
			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {			
					$_SESSION['role']= $ligne->role ;   // Stockage du role de l'utilisateur
					$connecte=true;
				}
			}
			return $connecte;
		}
		catch ( Exception $e ) {
			echo "<h1>Erreur de connexion à la base de données ! </h1><br/>";
			return false;
		} 
	}

    function connecteBD(&$erreurRenvoyee) {
		// Fonction permettant de se connecter à la base de données
		$PARAM_hote='localhost'; // le chemin vers le serveur
		$PARAM_port='3306';
		$PARAM_nom_bd='Eureka'; // le nom de votre base de données
		$PARAM_utilisateur='BDid'; // nom d'utilisateur pour se connecter
		$PARAM_mot_passe='mdpSecuriser'; // mot de passe de l'utilisateur pour se connecter
	
		global $connexion; // Permet de retrouver la connexion à l'extérieur de la fonction
	
		// connexion à la BD
		try{ // Bloc try bd injoignable			
			$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
			$connexion->exec('SET NAMES utf8'); // Réglage de la connexion en utf8
			return true ; //La connexion est établie
		}catch ( Exception $e ) {
			$erreurRenvoyee=$e->getMessage();
			return false ;//La connexion a échouée
		} 		
	}


	function listeDesEntreprise() {
		// Retourne la liste des entreprises participantes au forum sous forme de tableau
		global $connexion;  // Connexion à la BD
		
		$tableauRetour=array() ; // Tableau qui sera retourné contenant les entreprises participantes
		
		try {
			$maRequete = $connexion->prepare("
			CALL displayAllEntreprise();");

			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {
					$tabEntreprise['designation']=$ligne['Designation'];
					$tabEntreprise['secteur']=$ligne['secteur'];
					$tabEntreprise['logo']=$ligne['logo'];
					$tabEntreprise['presentation']=$ligne['presentation'];					
					$tableauRetour[]=$tabEntreprise;
				}
				return $tableauRetour;
			}
		}
		catch ( Exception $e ) {
			return $tableauRetour;
		}
	}

	function listeEntrepriseRecherche($chaineCaractere){
		// Retourne la liste des entreprises participantes au forum sous forme de tableau contenant la chaine
		// de caractère passé en parametre
		global $connexion;  // Connexion à la BD
		
		$tableauRetourR=array() ; // Tableau qui sera retourné contenant les entreprises participantes contenant 
		// la chaine de caractère $chaineCaractere
		
		try {
			$maRequete = $connexion->prepare("
			CALL displayEntrepriseByDesignation(".$chaineCaractere.");");
			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {
					$tabEntrepriseR['designation']=$ligne['Designation'];
					$tabEntrepriseR['secteur']=$ligne['secteur'];
					$tabEntrepriseR['logo']=$ligne['logo'];
					$tabEntrepriseR['presentation']=$ligne['presentation'];					
					$tableauRetourR[]=$tabEntrepriseR;
				}
				return $tableauRetourR;
			}
		}
		catch ( Exception $e ) {
			return $tableauRetourR;
		}
	}


	function deconnecteBD() {
		// Fonction permettant de se deconnecter de la BD en fin de script
		global $connexion; // Variable globale contenant la connexion
		$connexion=null;
	}
?>
<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////		
	function connecteBD(&$erreurRenvoyee) {
		// Fonction permettant de se connecter à la base de données
		$PARAM_hote='localhost'; // le chemin vers le serveur
		$PARAM_port='3306';
		$PARAM_nom_bd='eureka'; // le nom de votre base de données
		$PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
		$PARAM_mot_passe='root'; // mot de passe de l'utilisateur pour se connecter
	
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
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	function deconnecteBD() {
		// Fonction permettant de se deconnecter de la BD en fin de script
		global $connexion; // Variable globale contenant la connexion
		$connexion=null;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	function verifUtilisateur($login,$pwd) {
		// Vérifie si l'utilisateur existe
		// Les paramétres sont le login et le mot de passe envoyé par le formulaire de connexion
		// Renvoie vrai ou faux en fonction si l'utilisateur a été trouvé.
		// Si vrai, enregistrement dans les variables de session du nom du client, de son ID et d'une variable permettant de savoir que l'on est connecté.
		
		global $connexion; 
		try{ // Bloc try bd injoignable 
			$connecte=false;
			$maRequete = $connexion->prepare("CALL CONNEXION(:leLogin, :lePWD)");
			$maRequete->bindParam(':leLogin', $login);
			$maRequete->bindParam(':lePWD', $pwd);
			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {	
					if(isset($ligne->est_utilisateur) && $ligne->est_utilisateur ==0){
						return $connecte;
					}		
					$_SESSION['connecte']= true ; 			// Stockage dans les variables de session que l'on est connecté (sera utilisé sur les autres pages)
					$_SESSION['nomClient']= $ligne->$login ;   // Stockage dans les variables de session du nom du client
					$_SESSION['IdUser']= $ligne->id ;// Stockage dans les variables de session de l'Id du client
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
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////	

	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
?>
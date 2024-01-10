<?php 
$connexion;
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
			$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe); //TODO port non utiliser ?
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
	

	function getPDO() {
		global $connexion;
		return $connexion;
	}
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
					$_SESSION['IdUser']= $ligne->id ;// Stockage dans les variables de session de l'Id du client
					$_SESSION['role']= $ligne->id_role;
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
	
	function getIntervenantEntreprise($connexion,$idE) {
		global $connexion;
		
		$maRequete = $connexion->prepare("SELECT * FROM intervenants JOIN filiere ON intervenants.id_filiere = filiere.id WHERE id_entreprise = :idE");
		$maRequete->bindParam(':idE', $idE);
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        return $tableauRetourF;
	}



	function AjoutEntreprise($connexion,$nom,$activite,$presentation,$ville,$adresse,$codePostal,$logo){
		global $connexion;
		$codePostal = intval($codePostal);
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		/*$maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(:nomEntreprise,:activiteEntreprise,:logoEntreprise,
		:presentationEntreprise,:villeEntreprise,:codePostalEntreprise,:adresseEntreprise)");*/
		$maRequeteInsertion = $connexion->prepare("INSERT INTO entreprise (Designation , activity_sector, logo,presentation) 
		VALUES(:nomEntreprise, :activiteEntreprise , :logoEntreprise , :presentationEntreprise)");
		$maRequeteInsertion->bindParam(':nomEntreprise', $nom);
		$maRequeteInsertion->bindParam(':activiteEntreprise', $activite);
		$maRequeteInsertion->bindParam(':logoEntreprise', $logo);
		$maRequeteInsertion->bindParam(':presentationEntreprise', $presentation);
		var_dump($maRequeteInsertion);
		if($maRequeteInsertion->execute()){
		} else {
			return false;
		}
		var_dump($maRequeteInsertion);
		$id = $connexion->lastInsertId();
		$maRequeteInsertion = $connexion->prepare("INSERT INTO lieu (ville, cp, adresse , num_rue ,id_entreprise) 
		VALUES (:villeEntreprise, :codePostalEntreprise, :adresseEntreprise, :numRue , :idE)");
		$maRequeteInsertion->bindParam(':villeEntreprise', $ville);	
		$maRequeteInsertion->bindParam(':codePostalEntreprise', $codePostal);
		$maRequeteInsertion->bindParam(':numRue', $numRue); 
		$maRequeteInsertion->bindParam(':adresseEntreprise', $adresse); 
		$maRequeteInsertion->bindParam(':idE', $id); 

		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}

	function AjoutIntervenant($connexion,$nom,$filiere, $entreprise){
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("INSERT INTO intervenants (name,id_entreprise,id_filiere) VALUES (:nom , :entreprise, :filiere)");

		$maRequeteInsertion->bindParam(':nom', $nom);
		$maRequeteInsertion->bindParam(':entreprise', $entreprise);
		$maRequeteInsertion->bindParam(':filiere', $filiere);
		if($maRequeteInsertion->execute()){
		} else {
			return false;
		}
		$id = $connexion->lastInsertId();
		$maRequeteInsertion = $connexion->prepare("INSERT INTO filiereinterventions(id_filiere,id_intervenant) VALUES(:filiere , :idIn)");

		$maRequeteInsertion->bindParam(':filiere', $filiere);
		$maRequeteInsertion->bindParam(':idIn', $id);

		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}

	function modifIntervenant($connexion, $nomIntervenant, $idFiliere , $idIntervenant) {
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("UPDATE intervenants SET name = :nom , id_filiere = :idFiliere WHERE id = :idIn");

		$maRequeteInsertion->bindParam(':nom', $nomIntervenant);
		$maRequeteInsertion->bindParam('idFiliere', $idFiliere);
		$maRequeteInsertion->bindParam(':idIn', $idIntervenant);

		
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}
	}

	function AjoutUtilisateur($connexion,$nom,$prenom, $username,$mdp,$role,$filiere){
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("INSERT INTO utilisateur(nom,prenom,Username,password,id_role,id_filiere) 
		VALUES(:nom, :prenom, :username, :mdp, :role, :filiere)");

		$maRequeteInsertion->bindParam(':nom', $nom);
		$maRequeteInsertion->bindParam(':prenom', $prenom);
		$maRequeteInsertion->bindParam(':username', $username);
		$maRequeteInsertion->bindParam(':mdp', $mdp);
		$maRequeteInsertion->bindParam(':role', $role);
		$maRequeteInsertion->bindParam(':filiere', $filiere);
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}


	function AjoutUtilisateurCSV($connexion,$nom,$prenom, $username,$mdp,$role,$filiere){
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("INSERT INTO utilisateur(nom,prenom,Username,password,id_role,id_filiere) VALUES (:nom, :prenom, :username, :mdp, :role, :filiere)");

		$maRequeteInsertion->bindParam(':nom', $nom);
		$maRequeteInsertion->bindParam(':prenom', $prenom);
		$maRequeteInsertion->bindParam(':username', $username);
		$maRequeteInsertion->bindParam(':mdp', $mdp);
		$maRequeteInsertion->bindParam(':role', $role);
		$maRequeteInsertion->bindParam(':filiere', $filiere);
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}
	/////////////////////////////////////////////////////////////////////////////////////////////

?>

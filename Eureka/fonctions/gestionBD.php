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
					if(isset($ligne->est_Utilisateur) && $ligne->est_Utilisateur ==0){
						return $connecte;
					}		
					$_SESSION['connecte']= true ; 			// Stockage dans les variables de session que l'on est connecté (sera utilisé sur les autres pages)
					$_SESSION['IdUser']= $ligne->id ;// Stockage dans les variables de session de l'Id du client
					switch ($ligne->id_role) {
						case 1:
							$_SESSION['role'] = "Admin";
							break;
						case 3:
							$_SESSION['role'] = "Etudiant";
							break;
						case 2:
							$_SESSION['role'] = "Gestionnaire";
							break;
						default :
							throw new Exception();
					}
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
		
		$maRequete = $connexion->prepare("SELECT * FROM intervenants WHERE id_entreprise = :idE");
		$maRequete->bindParam(':idE', $idE);
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        return $tableauRetourF;
	}
	function getIntervenantFiliere($connexion,$idIntervenant) {
		global $connexion;
		$maRequete = $connexion->prepare("SELECT * FROM filiere JOIN filiereinterventions ON filiere.id = filiereinterventions.id_filiere WHERE id_intervenant = :idI");
		$maRequete->bindParam(':idI', $idIntervenant);
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
		:presentationEntreprise,:villeEntreprise,:codePostalEntreprise,:adresseEntreprise)");
		$maRequeteInsertion->bindParam(':nomEntreprise', $nom);
		$maRequeteInsertion->bindParam(':activiteEntreprise', $activite);
		$maRequeteInsertion->bindParam(':logoEntreprise', $logo);
		$maRequeteInsertion->bindParam(':presentationEntreprise', $presentation);
		$maRequeteInsertion->bindParam(':villeEntreprise', $ville);	
		$maRequeteInsertion->bindParam(':codePostalEntreprise', $codePostal);
		$maRequeteInsertion->bindParam(':adresseEntreprise', $adresse); */

		$maRequeteInsertion = $connexion->prepare("INSERT INTO entreprise (Designation , activity_sector, logo,presentation) 
		VALUES(:nomEntreprise, :activiteEntreprise , :logoEntreprise , :presentationEntreprise)");
		$maRequeteInsertion->bindParam(':nomEntreprise', $nom);
		$maRequeteInsertion->bindParam(':activiteEntreprise', $activite);
		$maRequeteInsertion->bindParam(':logoEntreprise', $logo);
		$maRequeteInsertion->bindParam(':presentationEntreprise', $presentation);
		if($maRequeteInsertion->execute()){
		} else {
			return false;
		}

		$id = $connexion->lastInsertId();

		
		$verif = verifLieu($ville,$codePostal,$adresse);

		if($verif == null) {

		
		$maRequeteInsertion = $connexion->prepare("INSERT INTO lieu (ville, cp, adresse ) 
		VALUES (:villeEntreprise, :codePostalEntreprise, :adresseEntreprise)");
		$maRequeteInsertion->bindParam(':villeEntreprise', $ville);	
		$maRequeteInsertion->bindParam(':codePostalEntreprise', $codePostal);
		$maRequeteInsertion->bindParam(':adresseEntreprise', $adresse); 
		if($maRequeteInsertion->execute()){
		} else {
			return false;
		}
		$idL = $connexion->lastInsertId();
		} else {
			$idL = $verif['id'];
		}

		$maRequeteInsertion = $connexion->prepare("INSERT INTO lieuentreprise(lieuentreprise.id_entreprise, lieuentreprise.id_lieu) VALUES (:idEn,:idLi)");
		$maRequeteInsertion->bindParam(':idEn', $id);	
		$maRequeteInsertion->bindParam(':idLi', $idL);
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}

	function AjoutIntervenant($connexion,$nom, $entreprise){
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */
		$maRequeteInsertion = $connexion->prepare("INSERT INTO intervenants (name,id_entreprise) VALUES (:nom , :entreprise)");

		$maRequeteInsertion->bindParam(':nom', $nom);
		$maRequeteInsertion->bindParam(':entreprise', $entreprise);
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}

	function AjoutFiliereIntervenant($connexion,$filiere, $idIntervenant){
		global $connexion;
		$maRequeteInsertion = $connexion->prepare("INSERT INTO filiereinterventions(id_filiere,id_intervenant) VALUES(:filiere , :idIn)");

		$maRequeteInsertion->bindParam(':filiere', $filiere);
		$maRequeteInsertion->bindParam(':idIn', $idIntervenant);

		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}


	}
	function getLieu($id) {
		global $connexion;
		$maRequete = $connexion->prepare("SELECT * FROM lieuentreprise WHERE lieuentreprise.id_entreprise =  :id");
		$maRequete->bindParam(':id', $id);
		$maRequete->execute();
		return $maRequete->fetch();

	}
	function verifLieu($ville,$codePostal,$adresse) {
		global $connexion;
		$maRequeteInsertion = $connexion->prepare("SELECT * FROM lieu 
		WHERE ville = :villeEntreprise AND cp =  :codePostalEntreprise AND adresse= :adresseEntreprise");
		$maRequeteInsertion->bindParam(':villeEntreprise', $ville);	
		$maRequeteInsertion->bindParam(':codePostalEntreprise', $codePostal);
		$maRequeteInsertion->bindParam(':adresseEntreprise', $adresse); 
		$maRequeteInsertion->execute();
		return  $maRequeteInsertion->fetch();

	}
	function modifIntervenant($connexion, $nomIntervenant , $idIntervenant) {
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("UPDATE intervenants SET name = :nom WHERE id = :idIn");

		$maRequeteInsertion->bindParam(':nom', $nomIntervenant);
		$maRequeteInsertion->bindParam(':idIn', $idIntervenant);

		
		if($maRequeteInsertion->execute()){
			return true;
		} else {
			return false;
		}
	}

	function getInfoEntreprise($connexion,$idE) {
        $tableauRetourF = array();
		$verif = getLieu($idE);
		if($verif == null) {
			$maRequete = $connexion->prepare("SELECT * FROM entreprise WHERE entreprise.id =:idEn");
			$maRequete->bindParam(':idEn', $idE);
        	if ($maRequete->execute()) {
            	$tableauRetourF = $maRequete->fetch();
        	}
       		 return $tableauRetourF;
		}
        $maRequete = $connexion->prepare("SELECT * FROM entreprise JOIN lieuentreprise ON entreprise.id = lieuentreprise.id_entreprise JOIN lieu ON lieuentreprise.id_lieu = lieu.id  WHERE entreprise.id = :idEn");
       //$maRequete = $connexion->prepare("SELECT * FROM entreprise WHERE entreprise.id =:idEn");
        $maRequete->bindParam(':idEn', $idE);
        if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetch();
        }
        
        return $tableauRetourF;

    }

	function modifEntreprise($connexion, $designation , $activite , $logo, $presentation, $id,$ville,$adresse,$cp) {
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */
		$verif = getLieu($id);
		if($verif == null) {
			$verifExiste = verifLieu($ville,$cp,$adresse);
			if($verifExiste == null) {

			
				$maRequeteInsertion = $connexion->prepare("INSERT INTO lieu (ville, cp, adresse ) 
				VALUES (:villeEntreprise, :codePostalEntreprise, :adresseEntreprise)");
				$maRequeteInsertion->bindParam(':villeEntreprise', $ville);	
				$maRequeteInsertion->bindParam(':codePostalEntreprise', $cp);
				$maRequeteInsertion->bindParam(':adresseEntreprise', $adresse); 
				if($maRequeteInsertion->execute()){
				} else {
					return false;
				}
				$idL = $connexion->lastInsertId();
				$maRequeteInsertion = $connexion->prepare("INSERT INTO lieuentreprise(lieuentreprise.id_entreprise, lieuentreprise.id_lieu) VALUES (:idEn,:idLi)");
				$maRequeteInsertion->bindParam(':idEn', $id);	
				$maRequeteInsertion->bindParam(':idLi', $idL);
				if($maRequeteInsertion->execute()){
				} else {
					return false;
				}
			} else {
				$idL = $verifExiste['id'];
				$maRequeteInsertion = $connexion->prepare("INSERT INTO lieuentreprise(lieuentreprise.id_entreprise, lieuentreprise.id_lieu) VALUES (:idEn,:idLi)");
				$maRequeteInsertion->bindParam(':idEn', $id);	
				$maRequeteInsertion->bindParam(':idLi', $idL);
				if($maRequeteInsertion->execute()){
				} else {
					return false;
				}
			}
		
		}
		$maRequete = $connexion->prepare("UPDATE entreprise SET Designation = :designation , activity_sector =:activity, logo=:logo , presentation=:presentation WHERE id = :idE");
		$maRequete->bindParam(':designation', $designation);
		$maRequete->bindParam(':activity', $activite);
		$maRequete->bindParam(':logo', $logo);
		$maRequete->bindParam(':presentation', $presentation);
		$maRequete->bindParam(':idE', $id);
		
		if($maRequete->execute()){
			
		} else {
			return false;
		}
		if($verif != null) {
			$maRequete = $connexion->prepare("UPDATE lieu JOIN lieuentreprise ON lieuentreprise.id_lieu = lieu.id SET ville= :ville, adresse = :adresse, cp = :cp WHERE id_entreprise =:idE ");
			$maRequete->bindParam(':ville', $ville);
			$maRequete->bindParam(':adresse', $adresse);
			$maRequete->bindParam(':cp', $cp);
			$maRequete->bindParam(':idE', $id);
			if($maRequete->execute()){
				return true;
			} else {
				return false;
			}
		}
		return true;
	}

	function deleteIntervenantFiliere($connexion, $idFiliere , $idIntervenant) {
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequete = $connexion->prepare("DELETE FROM filiereinterventions WHERE id_intervenant = :idIn AND id_filiere = :idFiliere" );

		$maRequete->bindParam('idFiliere', $idFiliere);
		$maRequete->bindParam(':idIn', $idIntervenant);

		
		if($maRequete->execute()){
			return true;
		} else {
			return false;
		}
	}

	function AjoutUtilisateur($connexion,$nom,$prenom, $username,$mdp,$role,$filiere){
		global $connexion;
		/* $maRequeteInsertion = $connexion->prepare("CALL AjoutEntreprise(a,a,a,
		a,a,12345,a)"); */

		$maRequeteInsertion = $connexion->prepare("INSERT INTO utilisateur(Username,nom,prenom,password,id_role,id_filiere) 
		VALUES(:username,:nom, :prenom, :mdp, :role, :filiere)");

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




	function recherche($recherche, $page){
		global $connexion; 
		$page = $page * 6;
        $maRequete = $connexion->prepare('SELECT * FROM Utilisateur WHERE Username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche LIMIT 6 OFFSET :page');
		$maRequete->bindValue(':recherche',  '%'.$recherche . '%', PDO::PARAM_STR);
		$maRequete->bindParam(':page', $page, PDO::PARAM_INT);
		$maRequete->execute();


        return $maRequete;
	}

	function rechercheByRole($recherche, $role, $page){
		global $connexion; 
		$page = $page * 6;
        $maRequete = $connexion->prepare('SELECT * FROM Utilisateur WHERE (Username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche) AND id_role =:role LIMIT 6 OFFSET :page');
		$maRequete->bindValue(':recherche',  '%'.$recherche . '%', PDO::PARAM_STR);
		$maRequete->bindParam(':role', $role);
		$maRequete->bindParam(':page', $page, PDO::PARAM_INT);
		$maRequete->execute();


        return $maRequete;
	}
	function modifyUsers( $username , $nom , $prenom , $motDePasse , $role , $filiere,$id){
		global $connexion; 
        $maRequete = $connexion->prepare("Update Utilisateur set username=:username,nom=:nom,prenom=:prenom,password=:motDePasse, id_role=:role, id_filiere=:filiere WHERE id=:id");
        $maRequete->bindParam(':username', $username);
        $maRequete->bindParam(':nom', $nom);
        $maRequete->bindParam(':prenom', $prenom);
        $maRequete->bindParam(':motDePasse', $motDePasse);
        $maRequete->bindParam(':role', $role);
        $maRequete->bindParam(':filiere', $filiere);
		$maRequete->bindParam(':id', $id);
        $maRequete->execute();

        return $maRequete;

    }

    function displayAllUsers($page){
		global $connexion;
		$page = $page * 6;
		$maRequete = $connexion->prepare("SELECT * FROM Utilisateur LIMIT 6 OFFSET :page;");
		$maRequete->bindParam(':page', $page, PDO::PARAM_INT); // Ajout de PDO::PARAM_INT pour indiquer que c'est un entier
		$maRequete->execute();
		
		return $maRequete->fetchAll();
		
    }
	function displayNBLigne($recherche){
		global $connexion; 
        $maRequete = $connexion->prepare("SELECT CEIL(COUNT(*) / 6) AS total_pages FROM Utilisateur WHERE username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche;");
		$maRequete->bindValue(':recherche',  '%'.$recherche . '%', PDO::PARAM_STR);
		$maRequete->execute();

		return $maRequete->fetchAll();
	}
    function displayAllRole(){
		global $connexion; 
        $maRequete = $connexion->prepare("select * FROM role"); 
        $maRequete->execute();

        return $maRequete->fetchAll();
    }

	function displayAllFiliere(){
		global $connexion; 
        $maRequete = $connexion->prepare("select * FROM filiere"); 
        $maRequete->execute();

        return $maRequete->fetchAll();
	}

	function displayNbLigneWithRole($recherche, $role){
		global $connexion; 
		$maRequete = $connexion->prepare("SELECT CEIL(COUNT(*) / 6) AS total_pages FROM Utilisateur WHERE (username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche) AND id_role = :role;");
		$maRequete->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
		$maRequete->bindParam(":role", $role);
		$maRequete->execute();
	
		return $maRequete->fetchAll();
	}

	function modifUtilisateur($connexion, $nom, $prenom , $username, $mdp, $role,  $idE) {
		global $connexion;
		$maRequete = $connexion->prepare("UPDATE utilisateur SET nom = :nom ,  prenom = :prenom, Username = :username, password = :password, id_role = :idRole WHERE id = :idE");

		$maRequete->bindParam(':nom', $nom);
		$maRequete->bindParam(':prenom', $prenom);
		$maRequete->bindParam(':username', $username);
		$maRequete->bindParam(':password', $mdp);
		$maRequete->bindParam(':idRole', $role);
		$maRequete->bindParam(':idE', $idE);


		if($maRequete->execute()){
			return true;
		} else {
			return false;
		}
	}
	function getIdRole($connexion, $idRole) {
		global $connexion;

		$maRequete = $connexion->prepare("SELECT id FROM role WHERE designation = :role");

		$maRequete->bindParam(':role', $idRole);
		
		if($maRequete->execute()){
			$idR = $maRequete->fetch();
		} else {
			return null;
		}
		return $idR[0] ;
	}

	function getRole($connexion, $idRole) {
		global $connexion;

		$maRequete = $connexion->prepare("SELECT designation FROM role WHERE id = :idrole");

		$maRequete->bindParam(':idrole', $idRole);
		
		if($maRequete->execute()){
			$idR = $maRequete->fetch();
		} else {
			return null;
		}
		return $idR[0] ;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	function ajoutFiliere($connexion,$designation,$annee,$abrev) {
		global $connexion;
		$maRequete = $connexion->prepare("INSERT INTO filiere (filiere.year, filiere.field , filiere.abreviation) VALUES(:annee, :desi, :abrev)");
		$maRequete->bindParam(':annee', $annee);
		$maRequete->bindParam(':abrev', $abrev);
		$maRequete->bindParam(':desi', $designation);
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        return $tableauRetourF;
	}

	function dateLimiteDepassee($connexion) {
		global $connexion;
		$maRequete = $connexion->prepare("CALL DateDuJourPasser()");
		$tableauRetourF = array();
		if ($maRequete->execute()) {
			if($maRequete->fetch()['Result'] == 1) {
				return true;
			} else {
				return false;
			}

		}
	}

	function suppressionDonnees($connexion) {
		global $connexion;
		$maRequete = $connexion->prepare("CALL ResetDonnee()");
		$tableauRetourF = array();
		if ($maRequete->execute()) {
			return true;

		}
		return false;
	}
?>

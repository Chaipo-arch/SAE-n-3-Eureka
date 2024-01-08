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
					$_SESSION['role']= $ligne->role;
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
	
	function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        global $connexion;  
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', $chaineCaractere);
        $maRequete->bindParam(':laFiliaire', $filiaire);
    
		$maRequete->execute();
		
		$tableauRetourF=$maRequete->fetchAll();
		
       
        return $tableauRetourF;
	} 


	function addUsers( $username , $nom , $prenom , $motDePasse , $role , $filiere){
		global $connexion; 
        $maRequete = $connexion->prepare("call CreateUser(:username, :nom, :prenom, :motDePasse, :role, :filiere)");
        $maRequete->bindParam(':username', $username);
        $maRequete->bindParam(':nom', $nom);
        $maRequete->bindParam(':prenom', $prenom);
        $maRequete->bindParam(':motDePasse', $motDePasse);
        $maRequete->bindParam(':role', $role);
        $maRequete->bindParam(':filiere', $filiere);
        $maRequete->execute();

        return $maRequete;

    }

	function recherche($recherche, $page){
		global $connexion; 
		$page = $page * 10;
        $maRequete = $connexion->prepare('SELECT * FROM Utilisateur WHERE Username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche LIMIT 10 OFFSET :page');
		$maRequete->bindValue(':recherche',  '%'.$recherche . '%', PDO::PARAM_STR);
		$maRequete->bindParam(':page', $page, PDO::PARAM_INT);
		$maRequete->execute();


        return $maRequete;
	}

	function rechercheByRole($recherche, $role, $page){
		global $connexion; 
		$page = $page * 10;
        $maRequete = $connexion->prepare('SELECT * FROM Utilisateur WHERE (Username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche) AND id_role =:role LIMIT 10 OFFSET :page');
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

    function displayAllUsers($page ){
		global $connexion;
		$page = $page * 10;
		$maRequete = $connexion->prepare("SELECT * FROM Utilisateur LIMIT 10 OFFSET :page;");
		$maRequete->bindParam(':page', $page, PDO::PARAM_INT); // Ajout de PDO::PARAM_INT pour indiquer que c'est un entier
		$maRequete->execute();
		
		return $maRequete->fetchAll();
		
    }
	function displayNBLigne($recherche){
		global $connexion; 
        $maRequete = $connexion->prepare("SELECT CEIL(COUNT(*) / 10) AS total_pages FROM Utilisateur WHERE username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche;");
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
		$maRequete = $connexion->prepare("SELECT CEIL(COUNT(*) / 10) AS total_pages FROM Utilisateur WHERE (username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche) AND id_role = :role;");
		$maRequete->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
		$maRequete->bindParam(":role", $role);
		$maRequete->execute();
	
		return $maRequete->fetchAll();
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////	

	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////
?>

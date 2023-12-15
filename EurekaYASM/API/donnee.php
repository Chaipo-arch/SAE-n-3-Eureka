<?php 
    function getPDO() {
        // Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='eureka';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];
		
		try{	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e){
			//Il y a eu une erreur de connexion
			$infos['Statut']="KO";
			$infos['message']="Problème connexion base de données";
			sendJSON($infos, 500) ;
			die();
		}
    }

    function affichageDonne() {
        try {
            $pdo=getPDO();
            $maRequete = "SELECT * FROM utilisateur ";
            
            $stmt = $pdo->prepare($maRequete);										// Préparation de la requête
			$stmt->execute();	
				
			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }

	function affichageStats(){
        try {
            $pdo=getPDO();
            $maRequete = "SELECT FILIERE.field, COUNT(Utilisateur.id) as nombreEtudiant 
            FROM Utilisateur
            JOIN FILIERE ON Utilisateur.id_filiere = FILIERE.id
            GROUP BY Utilisateur.id_filiere, FILIERE.field
            HAVING COUNT(Utilisateur.id) > 0;
            ";
            
            $stmt = $pdo->prepare($maRequete);										// Préparation de la requête
			$stmt->execute();	
				
			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }
    
?>
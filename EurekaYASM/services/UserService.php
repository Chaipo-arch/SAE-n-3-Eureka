<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

class UserService {

    
    function verifUtilisateur($connexion,$login,$pwd) {
		// Vérifie si l'utilisateur existe
		// Les paramétres sont le login et le mot de passe envoyé par le formulaire de connexion
		// Renvoie vrai ou faux en fonction si l'utilisateur a été trouvé.
		// Si vrai, enregistrement du role de l'utilisateur, utiliser par la suite pour utiliser des fonctionnalités différentes
		
		
		$connecte=false;
        $maRequete = $connexion->prepare("call connexion(:leLogin, :lePWD)");
        $maRequete->bindParam(':leLogin', $login);
        $maRequete->bindParam(':lePWD', $pwd);
        if ($maRequete->execute()) {
            $maRequete->setFetchMode(PDO::FETCH_OBJ);
            while ($ligne=$maRequete->fetch()) {	
                if(isset($ligne->est_utilisateur) && $ligne->est_utilisateur ==0){
                    return $connecte;
                }		
                $_SESSION['connecte']= true ; 			// Stockage dans les variables de session que l'on est connecté (sera utilisé sur les autres pages)
                $_SESSION['role']= $ligne->id_role ;
                $_SESSION['IdUser']= $ligne->id ;       // Stockage dans les variables de session de l'Id du client
                $connecte=true;
            }
        }
        return $connecte;
		
	}

}
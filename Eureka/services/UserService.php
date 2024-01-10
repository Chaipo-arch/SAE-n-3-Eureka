<?php


    /**
     * Vérifie si l'utilisateur existe
	 * Les paramétres sont le login et le mot de passe envoyé par le formulaire de connexion
     * Renvoie vrai ou faux en fonction si l'utilisateur a été trouvé.
	 * Si vrai, enregistrement du role de l'utilisateur, utiliser par la suite pour utiliser des fonctionnalités différentes
     */
    function verifUtilisateur($connexion,$login,$pwd) {
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
                $_SESSION['connecte'] = true ; 			// Stockage dans les variables de session que l'on est connecté (sera utilisé sur les autres pages)
                $_SESSION['IdUser'] = $ligne->id ;       // Stockage dans les variables de session de l'Id du client
                $connecte=true;
            }
        }
        return $connecte;
		
	}

    /**
     * Permet de récupérer les souhaits de la base de donnée
     */
    function getSouhait($connexion,$idEtudiant) {
        $_SESSION['souhait']  = array();
        $souhaits= array();
		$maRequete = $connexion->prepare("CALL getSouhait(:IdE)");
        $maRequete->bindParam(':IdE', $idEtudiant);
        if ($maRequete->execute()) {
            while ($ligne=$maRequete->fetch()) {	
                $souhaits[]= $ligne['id_entreprise'];
            }
        }
        $_SESSION['souhait'] = $souhaits;
	}

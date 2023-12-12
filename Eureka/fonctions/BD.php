<?php 
    function listeDesComptes() {
		// Retourne la liste des entreprises participantes au forum sous forme de tableau
		// Parametre $IdClient=Identifiant du client dans la BD pour lequel on veut la liste des comptes
		global $connexion;  // Connexion à la BD
		
		$tableauRetour=array() ; // Tableau qui sera retourné contenant les entreprises participantes
		
		try {
			$maRequete = $connexion->prepare("
				");

			$maRequete->bindParam(':LidClient', $_SESSION['IdClient']);
			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {
					$tabCompte['IdCompte']=$ligne->IdCompte;
					$tabCompte['NoCompte']=$ligne->NoCompte;
					$tabCompte['IdClient']=$ligne->IdClient;
					$tabCompte['libelle']=$ligne->libelle;
					$tabCompte['image']=$ligne->image;
					$tabCompte['solde']=$ligne->solde;
					
					$tableauRetour[]=$tabCompte;
				}
				return $tableauRetour;
			}
		}
		catch ( Exception $e ) {
			return $tableauRetour;
		}
	}
?>
<?php 
    function listeDesEntreprise() {
		// Retourne la liste des entreprises participantes au forum sous forme de tableau
		// Parametre $IdClient=Identifiant du client dans la BD pour lequel on veut la liste des comptes
		global $connexion;  // Connexion à la BD
		
		$tableauRetour=array() ; // Tableau qui sera retourné contenant les entreprises participantes
		
		try {
			$maRequete = $connexion->prepare("
				SELECT Designation,activity_sector AS secteur,logo,presentation FROM Entreprise");

			if ($maRequete->execute()) {
				$maRequete->setFetchMode(PDO::FETCH_OBJ);
				while ($ligne=$maRequete->fetch()) {
					$tabCompte['designation']=$ligne->Designation;
					$tabCompte['secteur']=$ligne->secteur;
					$tabCompte['logo']=$ligne->logo;
					$tabCompte['presentation']=$ligne->presentation;					
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
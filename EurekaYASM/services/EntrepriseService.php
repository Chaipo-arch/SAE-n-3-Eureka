<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EntrepriseService {
    /**
     * Générateur de la liste des entreprises participants au forum 
     * selon différentes critères un like sur la désignation et une filiaire donné
     */
    function listeEntrepriseFiliaire($connexion,$chaineCaractere,$filiaire){
		global $connexion;  // Connexion à la BD
		$connecte=false;
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', $chaineCaractere);
        $maRequete->bindParam(':laFiliaire', $filiaire);
        $tableauRetourF = array();
		if ($maRequete->execute()) {
			$maRequete->setFetchMode(PDO::FETCH_OBJ);
			while ($ligne=$maRequete->fetch()) {
				$tabEntrepriseF['designation']=$ligne['Designation'];
				$tabEntrepriseF['secteur']=$ligne['secteur'];
				$tabEntrepriseF['logo']=$ligne['logo'];
				$tabEntrepriseF['presentation']=$ligne['presentation'];					
				$tableauRetourF[]=$tabEntrepriseF;
			}		
		}
        return $tableauRetourF;
	} 
}
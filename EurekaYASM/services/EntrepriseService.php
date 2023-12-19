<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EntrepriseService {

    
    function Entreprise($connexion,$filiere) {
        
        $tableauRetourF = array();
        $recherche = "";
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', $recherche);
        
        $maRequete->bindParam(':laFiliaire', $filiere);
        if ($maRequete->execute()) {
             while ($ligne=$maRequete->fetch()) {
                $tabEntrepriseF['designation']=$ligne['Designation'];
                $tabEntrepriseF['secteur']=$ligne['activity_sector'];
                $tabEntrepriseF['logo']=$ligne['logo'];
                $tabEntrepriseF['presentation']=$ligne['presentation'];					
                $tableauRetourF[]=$tabEntrepriseF;
            }		
        }
        
        return $tableauRetourF;

    }
    function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', $chaineCaractere);
        $maRequete->bindParam(':laFiliaire', $filiaire);
        $tableauRetourF = array();
		if ($maRequete->execute()) {
			while ($ligne=$maRequete->fetch()) {
				$tabEntrepriseF['designation']=$ligne['Designation'];
				$tabEntrepriseF['secteur']=$ligne['activity_sector'];
				$tabEntrepriseF['logo']=$ligne['logo'];
				$tabEntrepriseF['presentation']=$ligne['presentation'];					
				$tableauRetourF[]=$tabEntrepriseF;
			}		
		}
        return $tableauRetourF;
	} 
}
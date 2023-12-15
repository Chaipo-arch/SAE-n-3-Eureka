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
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', "a");
        if($filiere == null) {
            $filiere = "";
        } 
        $maRequete->bindParam(':laFiliaire', $filiere);
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
        return false;

    }
    function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        global $connexion;  
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
        $_SESSION['listeEntrepriseRecherche'] = $tableauRetourF
        return $tableauRetourF;
	} 
}
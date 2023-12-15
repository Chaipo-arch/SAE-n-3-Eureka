<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EntrepriseService {
<<<<<<< HEAD

    function Entreprise($connexion,$filiere) {
        /*$maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
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
        return $tableauRetourF;*/
        return false;

    }
    function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        global $connexion;  
        /*$maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
=======
    /**
     * Générateur de la liste des entreprises participants au forum 
     * selon différentes critères un like sur la désignation et une filiaire donné
     */
    function listeEntrepriseFiliaire($connexion,$chaineCaractere,$filiaire){
		global $connexion;  // Connexion à la BD
		$connecte=false;
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
>>>>>>> 485420d206b13d863fcc6894c5f9babd8fa88d6b
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
<<<<<<< HEAD
        return $tableauRetourF;*/
        return false;
    }
		

=======
        return $tableauRetourF;
	} 
>>>>>>> 485420d206b13d863fcc6894c5f9babd8fa88d6b
}
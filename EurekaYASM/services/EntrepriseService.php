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
        if($filiere == "Toutes") {
            $maRequete = $connexion->query("CALL displayAllEntreprise()");
            while ($ligne=$maRequete->fetch()) {
                $tabEntrepriseF['designation']=$ligne['Designation'];
                $tabEntrepriseF['secteur']=$ligne['secteur'];
                $tabEntrepriseF['logo']=$ligne['logo'];
                $tabEntrepriseF['presentation']=$ligne['presentation'];					
                $tableauRetourF[]=$tabEntrepriseF;
            }		

        } else {
            $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
            $maRequete->bindParam(':laDesignation', '');
            
            $maRequete->bindParam(':laFiliaire', $filiere);
            
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
        }
        return $tableauRetourF;

    }
    function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        global $connexion;  
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliaire(:laDesignation,:laFiliaire)");
            $maRequete->bindParam(':laDesignation',$chaineCaractere);
            
            $maRequete->bindParam(':laFiliaire', $filiaire);
            
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
<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class FiliereService {

    function getFilieres($connexion) {
        $tabFiliere= [];
        $sql = "SELECT Field FROM filiere";
        $maRequete = $connexion->query($sql);
            while ($ligne=$maRequete->fetch()) {	
                $tabFiliere[] = $ligne->Designation ;
                
            }
        
        return $tabFiliere;
    }
    function getStudentFiliere($connexion,$id) {
        $tabFiliere= [];
        $sql = "SELECT filiere.field FROM utilisateur JOIN filiere ON utilisateur.id_filiere = filiere.id WHERE utilisateur.id_role = 3 AND utilisateur.id = :Stid";
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':Stid', $id);
             if ($maRequete->execute()) {
                $maRequete->setFetchMode(PDO::FETCH_OBJ);
                while ($ligne=$maRequete->fetch()) {	
                    $tabFiliere[] = $ligne->Designation ;
                    
                }
             }
            return $tabFiliere;
    }
}
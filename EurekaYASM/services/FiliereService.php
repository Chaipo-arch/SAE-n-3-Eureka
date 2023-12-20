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
        $tableauRetour = array();
        
        $sql = "CALL displayAllFiliere()";
        $maRequete = $connexion->prepare($sql);
        if ($maRequete->execute()) {
            while ($ligne=$maRequete->fetch()) {	
                $tabFiliere['field'] = $ligne['field'];  
                $tableauRetour[]=$tabFiliere;
            }
        }
        return $tableauRetour;
    }
    function getStudentFiliere($connexion,$id) {
        
        $sql = "SELECT filiere.field AS fili FROM utilisateur JOIN filiere ON utilisateur.id_filiere = filiere.id WHERE utilisateur.id_role = 3 AND utilisateur.id = :Stid";
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':Stid', $id);
             if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {	
                    $filiere['field'] =$ligne['fili'];  
                }
                
             }
            return $filiere['field'];
    }
}
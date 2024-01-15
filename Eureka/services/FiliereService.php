<?php


    function getFilieres($connexion) {
        $tableauRetour = array();
        
        $sql = " SELECT DISTINCT field,id FROM filiere GROUP BY field ORDER BY field";
        $maRequete = $connexion->prepare($sql);
        if ($maRequete->execute()) {
            while ($ligne=$maRequete->fetch()) {	
                $tabFiliere['field'] = $ligne['field'];  
                $tabFiliere['id'] = $ligne['id'];  
                $tableauRetour[]=$tabFiliere;
            }
        }
        return $tableauRetour;
    }
    
    function getStudentFiliere($connexion,$id) {
        $filiere['field'] = null;
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
    function getIdFiliere($connexion,$nomFiliere) {
        $sql = "SELECT id FROM filiere WHERE filiere.field = :nomFiliere";
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':nomFiliere', $nomFiliere);
        $filiere['id'] = null;
             if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {	
                    $filiere['id'] =$ligne['id'];  
                }
                
             }
            return $filiere['id'];
    }

<?php


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
        $filiere['field'] = null;
        $sql = "SELECT filiere.field AS fili FROM utilisateur JOIN filiere ON utilisateur.id_filiere = filiere.id WHERE utilisateur.id_role = 3 AND utilisateur.id = :Stid";
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':Stid', $id);
             if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {
                    var_dump($ligne)	;
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

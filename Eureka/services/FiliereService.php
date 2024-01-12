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
    function getFilieresNoD($connexion) {
        $tableauRetour = array();
        
        $sql = " SELECT * FROM filiere";
        $maRequete = $connexion->prepare($sql);
        if ($maRequete->execute()) {
            while ($ligne=$maRequete->fetch()) {	
                $tabFiliere['field'] = $ligne['field'];  
                $tabFiliere['annee'] = $ligne['year'];
                $tabFiliere['abreviation'] = $ligne['abreviation'];
                $tabFiliere['id'] = $ligne['id'];
                $tableauRetour[]=$tabFiliere;
            }
        }
        return $tableauRetour;
    }
    function modifierFiliere($connexion,$annee,$field,$abreviation,$id) {
        
        $sql = " UPDATE filiere SET field =:field , year=:annee , abreviation =:abrev WHERE id = :idF";
        var_dump($sql);
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':field', $field);
        $maRequete->bindParam(':annee', $annee);
        $maRequete->bindParam(':abrev', $abreviation);
        $maRequete->bindParam(':idF', $id);
        if ($maRequete->execute()) {
            return true;
        }
        return false;
    }
    function deleteFiliere($connexion,$id) {
        
        $sql = " DELETE FROM filiere WHERE id = :idF";
        $maRequete = $connexion->prepare($sql);
        $maRequete->bindParam(':idF', $id);
        if ($maRequete->execute()) {
            return true;
        }
        return false;
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

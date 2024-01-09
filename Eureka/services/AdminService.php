<?php



    /**
     * Permet de modifier les caractéristiques du forum
     */
     function ModifierForumCaracteristiques($pdo,$date,$dateLimite,$duree) {
        try {
        $maRequete = $pdo->prepare("CALL editForum(:date,:duree,:dateLimite)");
        $maRequete->bindParam(':date', $date);
        $maRequete->bindParam(':duree', $duree);
        $maRequete->bindParam(':dateLimite', $dateLimite);
        $maRequete->execute();
        } catch(PODException $e) {
            return false;
        }
    }

    function getRoleId($pdo,$role) {
        $maRequete = $pdo->prepare("SELECT id FROM role WHERE designation = :rolee ");
        $maRequete->bindParam(':rolee', $role);
       
        $maRequete->execute();
        $roles['ide'] = null;
             if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {	
                    $roles['ide'] =$ligne['id'];  
                }
                
             }
            return $roles['ide'];
        
    }

    

    function deleteEntreprise($pdo,$idE) {
        $maRequete = $pdo->prepare("DELETE souhaitetudiant FROM souhaitetudiant WHERE souhaitetudiant.id_entreprise = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
        
        $maRequete = $pdo->prepare("DELETE souhaitentreprise FROM souhaitentreprise WHERE souhaitentreprise.id_entreprise = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
        
        $maRequete = $pdo->prepare("DELETE filiereinterventions FROM filiereinterventions JOIN intervenants ON filiereinterventions.id_intervenant = intervenants.id WHERE intervenants.id_entreprise = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
        
        $maRequete = $pdo->prepare("DELETE FROM lieu WHERE lieu.id_entreprise = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();

        $maRequete = $pdo->prepare("DELETE intervenants FROM intervenants WHERE intervenants.id_entreprise = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();

        $maRequete = $pdo->prepare("DELETE FROM entreprise WHERE entreprise.id = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();

    }

    function ModifierEntreprise($pdo,$idE) {
        $maRequete = $pdo->prepare("");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();

    }
    function ModifierIntervenant($pdo,$idIn) {
        $maRequete = $pdo->prepare("");
        $maRequete->bindParam(':idE', $idIn);
        $maRequete->execute();

    }
    function SupprimerIntervenant($pdo,$idIn) {
        $maRequete = $pdo->prepare("DELETE filiereinterventions FROM filiereinterventions WHERE filiereinterventions.id_intervenant = :idIn");
        $maRequete->bindParam(':idIn', $idIn);
        $maRequete->execute();
        $maRequete = $pdo->prepare("DELETE intervenants FROM intervenants WHERE intervenants.id = :idIn");
        $maRequete->bindParam(':idIn', $idIn);
        $maRequete->execute();
    }

    function deleteEtudiant($pdo,$idE) {
        $maRequete = $pdo->prepare("DELETE souhaitetudiant FROM souhaitetudiant WHERE souhaitetudiant.id_utilisateur = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
        
        $maRequete = $pdo->prepare("DELETE utilisateur FROM utilisateur WHERE utilisateur.id = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
        

    }
    function deleteUser($pdo,$idE) {
        $maRequete = $pdo->prepare("DELETE utilisateur FROM utilisateur WHERE utilisateur.id = :idE");
        $maRequete->bindParam(':idE', $idE);
        $maRequete->execute();
    }
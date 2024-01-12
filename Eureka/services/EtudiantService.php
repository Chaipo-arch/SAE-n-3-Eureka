<?php
    /**
      *  Recupere les étudiants avec leur souhaits
     */
    function getStudentWithsouhaits($connexion) {
	$maRequete = $connexion->prepare("CALL displayEtudiantEtSouhait()");
        $tableauEtudiant = array();
        $tableauRetour= array();
        if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {
                        $tableauEtudiant['id'] = $ligne['id'];
                        $tableauEtudiant['nom'] = $ligne['nom'];
                        $tableauEtudiant['prenom'] = $ligne['prenom'];
                        $tableauEtudiant['field'] = $ligne['field'];
                        $tableauRetour[] = $tableauEtudiant;
                }
        }
        return $tableauRetour;
	}

        function etudiant($connexion,$filiere) {
                return false;
                $filiere = htmlspecialchars($filiere);
                $tableauRetourF = array();
                $recherche = "";
                $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
                $maRequete->bindParam(':laDesignation', $recherche);
                
                $maRequete->bindParam(':laFiliaire', $filiere);
        
                if ($maRequete->execute()) {
                    $tableauRetourF = $maRequete->fetchAll();
                }
                
                return $tableauRetourF;
            }

            function rechercheEtudiant($connexion,$chaineCaractere,$filiaire){
                return false;
                $maRequete = $connexion->prepare("SELECT * FROM Utilisateur WHERE (Username LIKE :recherche OR nom LIKE :recherche OR prenom LIKE :recherche)");
                $maRequete->bindParam(':laDesignation', $chaineCaractere);
                $maRequete->bindParam(':laFiliaire', $filiaire);
                $tableauRetourF = array();
                if ($maRequete->execute()) {
                    $tableauRetourF = $maRequete->fetchAll();            
                }
                var_dump($tableauRetourF);
                var_dump($tableauRetourF);
                return $tableauRetourF;
        } 

        function getSouhaitE($connexion,$idEtudiant) {
                $souhaits= array();
                $maRequete = $connexion->prepare("CALL getSouhait(:IdE)");
                $maRequete->bindParam(':IdE', $idEtudiant);
                if ($maRequete->execute()) {
                    while ($ligne=$maRequete->fetch()) {	
                        $souhaits[]= $ligne['id_entreprise'];
                    }
                }
                return $souhaits;
        }
        function getSouhaitEtudiantEntier($connexion,$idEtudiant) {
            $souhaits= array();
            $maRequete = $connexion->prepare("SELECT * FROM entreprise JOIN intervenants ON entreprise.id = intervenants.id_entreprise JOIN souhaitetudiant ON intervenants.id = souhaitetudiant.id_intervenant WHERE souhaitetudiant.id_utilisateur =  :idE ");
            $maRequete->bindParam(':idE', $idEtudiant);
            if ($maRequete->execute()) {
                while ($ligne=$maRequete->fetch()) {	
                    $souhaits[]= $ligne;
                }
            }
            return $souhaits;
    }


<?php 
    function generationPlanningEntier($connexion) {
        
        $caracteristiques = getForumCaracteristiques($connexion);
        $heureFin =strtotime($caracteristiques['Heure_fin'] );
        $heureDebut = strtotime( $caracteristiques['Heure_debut'] );
        $dureeRDV = strtotime($caracteristiques['duree']);
        $maRequete = $connexion->prepare("SELECT id FROM intervenants");
        $maRequete->execute();
        
        $tabIntervenant=$maRequete->fetchAll();
        $tabPriorite= array();
        foreach($tabIntervenant as $Intervenant) {
             generationPriorite($connexion,$Intervenant['id']) ;

        }
        $maRequete = $connexion->prepare("select * FROM intervenants  ORDER BY  est_disponible DESC");
        $maRequete->bindParam(':lIntervenant', $idIntervenant);
        $maRequete->execute();
        $tabPriorite= $maRequete->fetchAll();
        //$heureFRendezVous = $caracteristiques['Heure_debut'] +$dureeRDV;
        $tabSouhait= array();
        foreach($tabPriorite as $Intervenant) {
            if($Intervenant['est_disponible'] != -1) {
                $maRequete = $connexion->prepare("SELECT souhaitetudiant.id as souhait FROM Intervenants
                JOIN souhaitEtudiant
                ON souhaitEtudiant.id_intervenant = Intervenants.id
                WHERE souhaitEtudiant.id_intervenant = :lIntervenant ");
                
                $maRequete->bindParam(':lIntervenant', $Intervenant['id']);
                $maRequete->execute();
                while ($ligne=$maRequete->fetch()) {
                    $tab['souhait'][] = $ligne['souhait'];
                }
                $tabSouhait[] = $tab;
                $tab['souhait'] = null;
            }
        }
        $maRequete = $connexion->prepare("DELETE FROM rdv");

                     $maRequete->execute();
        foreach($tabSouhait as $souhaits) {
            $heureDebut = $caracteristiques['Heure_debut'] ;
            if(isset($heureFin['temps'])) {
                $heureFin['temps'] = null;
            }
            $nbP = 0;
            $valide = false;
            $valeurMax = false;
            while($souhaits['souhait'] != null && !$valeurMax) {
                if(isset($heureFin['temps']) && !$valide) {
                    $ajoutPause = "00:01:00";
                    $maRequete = $connexion->prepare("CALL AjoutTime(:base,:ajout)");
                    $maRequete->bindParam(':base', $heureFin['temps']);
                    $maRequete->bindParam(':ajout', $ajoutPause);
                    $maRequete->execute();
                    $heureFin= $maRequete->fetch();
                    $heureDebut = $heureFin['temps'];
                    
                }
                if(!$valide) {
                    $maRequete = $connexion->prepare("CALL AjoutTime(:base,:ajout)");
                    $maRequete->bindParam(':base', $heureDebut);
                    $maRequete->bindParam(':ajout',$caracteristiques['duree'] );
                    $maRequete->execute();
                    $heureFin= $maRequete->fetch();
                }
            foreach($souhaits['souhait'] as $souhait ) {          
                    $nbP = $nbP +1;
                if(strtotime($heureFin['temps']) <= strtotime($caracteristiques['Heure_fin']) ) {
                    $maRequete = $connexion->prepare("CALL creneauxDisponible(:souhait,:heureD,:heureF)");
                    $maRequete->bindParam(':souhait', $souhait);
                    $maRequete->bindParam(':heureD',$heureDebut );
                    $maRequete->bindParam(':heureF',$heureFin['temps'] );
                     $maRequete->execute();
                     $ligne = $maRequete->fetch();
                    if($ligne[0][0] == 0) {
                        array_splice($souhaits['souhait'],0,$nbP);
                        $nbP = 0;
                        $souhaits['souhait'][] = $souhait;
                        $valide = false;
                    } else {
                        array_splice($souhaits['souhait'],0,$nbP);
                        $nbP = 0;
                        $ajoutPause = "00:01:00";
                        $maRequete = $connexion->prepare("CALL AjoutTime(:base,:ajout)");
                        $maRequete->bindParam(':base', $heureFin['temps']);
                        $maRequete->bindParam(':ajout', $ajoutPause);
                        $maRequete->execute();
                        $heureFin= $maRequete->fetch();
                        $heureDebut = $heureFin['temps'];
                        $maRequete = $connexion->prepare("CALL AjoutTime(:base,:ajout)");
                         $maRequete->bindParam(':base', $heureDebut);
                        $maRequete->bindParam(':ajout',$caracteristiques['duree'] );
                        $maRequete->execute();
                        $heureFin= $maRequete->fetch();
                        $valide = true;
                    
                    }
                    
                 } else {
                    $valeurMax = true;
                 }
                }
            }
        }
    
    }
    
    function generationPriorite($connexion,$idIntervenant) {
        /*$heureDebut = strtotime("1970-01-01 $heureDebutForum UTC");
        $heureFin = strtotime("1970-01-01 $heureFinForum UTC");*/

        $planning = array();
        $tabIntervenant[] = array();
        
        $maRequete = $connexion->prepare("CALL getIntervenantPaticipant(:lIntervenant)");
        $maRequete->bindParam(':lIntervenant', $idIntervenant);
        $maRequete->execute();
       $ligne = $maRequete->fetch();
        return $ligne;

       /* if (($heureFin - $heureDebut) % $dureeRDV == 0) {
                $nbCreneaux =  ($heureFin - $heureDebut) / $dureeRDV;
        } else {
                $nbCreneaux = ceil(($heureFin - $heureDebut) / $dureeRDV);
        }
        $heureDepart = $heureDebutForum;
        for ($i = 0; $i < $nbCreneaux; $i++) {
            $heure = date("H:i", strtotime("$heureDepart +5 minutes"));
            $planning[] = array('listeVide' => array(), 'chaineIncrementee' => $heure);
            $heureDepart = $heure;
        }  */ 
    }
    function chargementPlanning($connexion,$planningARemplir){
        
        return $planningCharge;
    }

    function voirPlanning($connexion,$idEtudiant, $visibiliteAdmin) {
        if($visibiliteAdmin) {
            $maRequete = $connexion->prepare("SELECT * FROM rdv JOIN souhaitetudiant ON rdv.id_souhait=souhaitetudiant.id JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id JOIN entreprise ON intervenants.id_entreprise = entreprise.id JOIN utilisateur ON souhaitetudiant.id_utilisateur = utilisateur.id WHERE souhaitetudiant.id_Utilisateur = :idE ORDER BY rdv.Heure_debut");
            $maRequete->bindParam(':idE', $idEtudiant);

            $maRequete->execute();
            $ligne = $maRequete->fetchAll();
        } else {
            $maRequete = $connexion->prepare("SELECT * FROM rdv JOIN souhaitetudiant ON rdv.id_souhait=souhaitetudiant.id JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id JOIN entreprise ON intervenants.id_entreprise = entreprise.id JOIN utilisateur ON souhaitetudiant.id_utilisateur = utilisateur.id  WHERE souhaitetudiant.id_Utilisateur = :idE AND rdv.est_valide = 1 ORDER BY rdv.Heure_debut");
            $maRequete->bindParam(':idE', $idEtudiant);

            $maRequete->execute();
            $ligne = $maRequete->fetchAll();
        }
     
        return $ligne;
    }
    function voirPlanningEntreprise($connexion,$idEntreprise, $visibiliteAdmin) {
        if($visibiliteAdmin) {
            $maRequete = $connexion->prepare("SELECT * FROM rdv JOIN souhaitetudiant ON rdv.id_souhait=souhaitetudiant.id JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id JOIN entreprise ON intervenants.id_entreprise = entreprise.id  JOIN utilisateur ON souhaitetudiant.id_utilisateur = utilisateur.id WHERE entreprise.id = :idE ORDER BY rdv.Heure_debut ");
            $maRequete->bindParam(':idE', $idEntreprise);

            $maRequete->execute();
            $ligne = $maRequete->fetchAll();
        } else {
            $maRequete = $connexion->prepare("SELECT * FROM rdv JOIN souhaitetudiant ON rdv.id_souhait=souhaitetudiant.id JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id JOIN entreprise ON intervenants.id_entreprise = entreprise.id JOIN utilisateur ON souhaitetudiant.id_utilisateur = utilisateur.id WHERE entreprise.id = :idE AND rdv.est_valide = 1 ORDER BY rdv.Heure_debut");
            $maRequete->bindParam(':idE', $idEntreprise);

            $maRequete->execute();
            $ligne = $maRequete->fetchAll();
        }
        return $ligne;
    } 

    function ValiderTousLesPlanning($connexion) {
        $maRequete = $connexion->prepare("UPDATE rdv SET est_valide = 1 ");
        $maRequete->execute();
    }
    function ValiderPlanning($connexion,$idP,$categorie) {
        if($categorie == 1) {
            $maRequete = $connexion->prepare("UPDATE rdv JOIN souhaitetudiant ON souhaitetudiant.id = rdv.id_souhait SET est_valide = 1 WHERE souhaitetudiant.id_Utilisateur = :idP ");
            $maRequete->bindParam(':idP', $idP);
            $maRequete->execute();
        } else if($categorie == 2) {

        
            $maRequete = $connexion->prepare("UPDATE rdv JOIN souhaitetudiant ON souhaitetudiant.id = rdv.id_souhait SET est_valide = 1 WHERE souhaitetudiant.id_intervenant = :idP");
            $maRequete->bindParam(':idP', $idP);
            $maRequete->execute();
        }
    }

    /*ENtreprise sans planning : SELECT Designation , id FROM entreprise  WHERE id not IN (SELECT entreprise.id FROM rdv JOIN souhaitetudiant ON rdv.id_souhait=souhaitetudiant.id JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id JOIN entreprise ON intervenants.id_entreprise = entreprise.id /*AND rdv.est_valide = 1)*/
    



    
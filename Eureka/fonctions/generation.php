<?php 
    function generationPlanningEntier($connexion) {
        include("../services/ForumService.php");
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
        var_dump($tabPriorite);
        foreach($tabPriorite as $Intervenant) {
            var_dump($Intervenant);
            if($Intervenant['est_disponible'] != -1) {
                if (($heureFin - $heureDebut) % $dureeRDV == 0) {
                    $nbCreneaux =  ($heureFin -$heureDebut) / $dureeRDV;
                } else {
                        $nbCreneaux = ceil(($heureFin - $heureDebut) / $dureeRDV);
                }
                $heureDepart = $caracteristiques['Heure_debut'];
                for ($i = 0; $i < $nbCreneaux; $i++) {
                    $heure = date("H:i", strtotime("$heureDepart +5 minutes"));
                    $planning[] = array('listeVide' => array(), 'chaineIncrementee' => $heure);
                    $heureDepart = $heure;
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

    /*DROP FUNCTION IF EXISTS priorite(
    p_nbSouhait INTEGER)
RETURNS INTEGER AS $$
DELIMITER //

CREATE FUNCTION priorite(
    p_nbSouhait INT)
RETURNS INT
BEGIN
    DECLARE dureeForum INT;
    DECLARE dureeRendezVous INT;
    DECLARE minutes INT;
    DECLARE priorite INT;

    SELECT p_duree_par_default INTO dureeRendezVous FROM Forum;
    SELECT TIMEDIFF(heure_Fin,heure_debut)INTO dureeForum FROM Forum  ;
    SET minutes = HOUR(dureeForum) *60 + MINUTE(dureeForum);
    SET priorite = p_nbSouhait ;
    IF priorite >1 THEN 
        RETURN -1;
    ELSE
        RETURN priorite *100;
    END IF ;
    
    
    
    
END//
DELIMITER ;*/

<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EtudiantService {

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
                        $tableauEtudiant['souhait'] = array();
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
                $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
                $maRequete->bindParam(':laDesignation', $chaineCaractere);
                $maRequete->bindParam(':laFiliaire', $filiaire);
                $tableauRetourF = array();
                if ($maRequete->execute()) {
                    $tableauRetourF = $maRequete->fetchAll();            
                }
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
}

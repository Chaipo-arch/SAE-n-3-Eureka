<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EntrepriseService {

    
    function Entreprise($connexion,$filiere) {
        
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

    function getEntreprise($connexion,$idSouhait) {
        $tableauRetourF = array();
        $maRequete = $connexion->prepare("SELECT * FROM entreprise WHERE ID = :idEn");
        $maRequete->bindParam(':idEn', $idSouhait);
        if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();
        }
        return $tableauRetourF[0];

    }

    function RechercheEntreprise($connexion,$chaineCaractere,$filiaire){
        $maRequete = $connexion->prepare("CALL displayEntrepriseByDesignationByFiliere(:laDesignation,:laFiliaire)");
        $maRequete->bindParam(':laDesignation', $chaineCaractere);
        $maRequete->bindParam(':laFiliaire', $filiaire);
        $tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();
				
		}
        return $tableauRetourF;
	} 

    function createSouhait($connexion,$idEntreprise,$idEtudiant) {
        $maRequete = $connexion->prepare("CALL setSouhait(:idEntreprise, :idEtudiant)");
        $maRequete->bindParam(':idEntreprise', $idEntreprise);
        $maRequete->bindParam(':idEtudiant', $idEtudiant);
        $maRequete->execute();	
	} 

    function deleteSouhait($connexion,$idEntreprise,$idEtudiant) {
        $maRequete = $connexion->prepare("CALL deleteSouhait(:idEntreprise, :idEtudiant)");
        $maRequete->bindParam(':idEntreprise', $idEntreprise);
        $maRequete->bindParam(':idEtudiant', $idEtudiant);
        $maRequete->execute();	
        
	} 
}
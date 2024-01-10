<?php


    
    function Entreprise($connexion,$filiere) {
        $filiere = htmlspecialchars($filiere);
        $tableauRetourF = array();
        $recherche = "";
        $maRequete = $connexion->prepare(" SELECT Entreprise.Id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation
        FROM Entreprise 
        
        WHERE Designation LIKE CONCAT('%', :laDesignation, '%')");
        $maRequete->bindParam(':laDesignation', $recherche);
        

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

    function getInfoEntreprise($connexion,$idE) {
        $tableauRetourF = array();
        $maRequete = $connexion->prepare("SELECT * FROM entreprise JOIN lieu ON entreprise.id = lieu.id_entreprise WHERE entreprise.id = :idEn");
        $maRequete->bindParam(':idEn', $idE);
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

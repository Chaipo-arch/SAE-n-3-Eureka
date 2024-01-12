<?php

    function getForumCaracteristiques($pdo) {
        $caracteristiques = array();
        $maRequete=$pdo->prepare("SELECT * FROM forum");
        $maRequete->execute();
        while ($ligne=$maRequete->fetch()) {
            $caracteristiques['date'] = $ligne['date_debut'];
            $caracteristiques['dateLimite'] = $ligne['date_limite'];
            $caracteristiques['duree'] = $ligne['p_duree_par_default'];
            $caracteristiques['Heure_debut'] = $ligne['heure_debut'];
            $caracteristiques['Heure_fin'] = $ligne['heure_fin'];
        }
        return $caracteristiques;
    }
    function getAllUser($pdo) {
        $utilisateur = array();
        $maRequete=$pdo->prepare("SELECT * FROM utilisateur");
        $maRequete->execute();
        $utilisateur = $maRequete->fetchAll();
        return $utilisateur;
    }
    function getInfoEtudiant($connexion,$idE) {
        $tableauRetourF = array();
        $maRequete = $connexion->prepare("SELECT * FROM utilisateur WHERE id = :idEn");
        $maRequete->bindParam(':idEn', $idE);
        if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetch();
        }
        return $tableauRetourF;

    }

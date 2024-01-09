<?php

    function getForumCaracteristiques($pdo) {
        $caracteristiques = array();
        $maRequete=$pdo->prepare("SELECT * FROM forum");
        $maRequete->execute();
        while ($ligne=$maRequete->fetch()) {
            $caracteristiques['date'] = "";
            $caracteristiques['dateLimite'] = $ligne['date_limite'];;
            $caracteristiques['duree'] = $ligne['duree'];
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

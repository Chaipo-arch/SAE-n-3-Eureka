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

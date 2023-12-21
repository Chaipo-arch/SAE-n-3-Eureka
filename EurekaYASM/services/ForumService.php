<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour le forum, ne prend pas en compte la logique de l'admin sur le forum
 */
class ForumService {
    public function getForumCaracteristiques($pdo) {
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
}
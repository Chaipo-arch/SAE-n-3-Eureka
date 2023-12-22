<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class AdminService {


    /**
     * Permet de modifier les caractéristiques du forum
     */
    public function ModifierForumCaracteristiques($pdo,$date,$dateLimite,$duree) {
        try {
        $maRequete = $connexion->prepare("CALL editForum(:date,:duree,:dateLimite)");
        $maRequete->bindParam(':date', $date);
        $maRequete->bindParam(':duree', $duree);
        $maRequete->bindParam(':dateLimite', $dateLimite);
        } catch(PODException $e) {
            return false;
        }
    }
}
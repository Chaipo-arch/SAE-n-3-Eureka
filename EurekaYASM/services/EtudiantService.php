<?php
namespace services;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

/**
 * Classe gérant la logique pour les entreprises
 */
class EtudiantService {
    function getStudentWithsouhaits($connexion) {
		$maRequete = $connexion->prepare("CALL DisplayAllStudent()");
        $tableauRetourF = array();
        if ($maRequete->execute()) {	
                $tableauRetourF = $maRequete->fetchAll();
        }
        return $tableauRetourF;
	}
}
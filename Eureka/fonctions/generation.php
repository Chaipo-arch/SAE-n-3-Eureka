<?php 
    function generationPlanning($connexion,$duree, $type) {
        $maRequete = $connexion->prepare("SELECT id FROM souhaitetudiant ");
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        $souhaitEtudiants =  $tableauRetourF;
        $maRequete = $connexion->prepare("SELECT id FROM intervenants");
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        $intervenants =  $tableauRetourF;
        $souhaitEtudiants =  $tableauRetourF;
        $maRequete = $connexion->prepare("SELECT id FROM utilisateur WHERE id_role = 3");
		$tableauRetourF = array();
		if ($maRequete->execute()) {
            $tableauRetourF = $maRequete->fetchAll();	
		}
        $etudiants =  $tableauRetourF;
        var_dump($souhaitEtudiants);
        var_dump($souhaitEtudiants);
        var_dump($souhaitEtudiants);
        var_dump($intervenants);
        var_dump($intervenants);
        var_dump($intervenants);
        var_dump($etudiants);
    }
<?php
    function getPlanningEntreprise($connexion , $idEntreprise){
        $resultat = $connexion->prepare("Select * from rdv JOIN intervenants ON id_intervenant = intervenants.id where id_entreprise = :idEntreprise ");
        $resultat->bindParam(":idEntreprise",$idEntreprise);
        $resultat->execute();
        return $resultat->fetchAll();
    }
    
    function genPointEntreprise($connexion,$idEntreprise){
        $resultat = $connexion->prepare("Call");
    }


?>
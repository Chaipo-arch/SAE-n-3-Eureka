<?php
namespace controllers;

use yasmf\View;
use PDO;
use services\EtudiantService;
use services\FiliereService;

class SouhaitController {


    public function __construct(EtudiantService $EtudiantService, FiliereService $filiereService) {
        $this->EtudiantService = $EtudiantService;
        $this->filiereService = $filiereService;
    }

    public function index($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/souhait");
        $_SESSION['nomPage'] = "souhait";
        return $view;
    }


}

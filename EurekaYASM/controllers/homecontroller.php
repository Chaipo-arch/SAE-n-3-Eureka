<?php
namespace controllers;

use yasmf\View;
use userService\UserService;
use PDO;

class HomeController {

    /*private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService; 
    }*/

    public function index($pdo) {
        //$searchStmt = $this->userService->getCategories($pdo);
        $sql = "select code_categorie, designation 
        from a_categories
        order by code_categorie";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        $view = new View("/views/all_categories");
        $view->setVar('searchStmt',$searchStmt);
        return $view;
    }

}



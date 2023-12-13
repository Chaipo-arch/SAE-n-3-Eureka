<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use userService\UserService;
use PDO;


class ArticlesController {

    /*private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService; 
    }*/

    public function index($pdo) {
        //$userService = new UserService();
        $codeCategorie = HttpHelper::getParam("code_categorie");
        $designationCategorie = HttpHelper::getParam("categorie");
        //$searchStmt = $userService->getArticles($pdo, $codeCategorie, $designationCategorie);
        $sql = "select id_article, code_article, designation 
            from articles
            where categorie = ?
            order by code_article";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute([$codeCategorie]);
        $view = new View("/views/all_articles");
        $view->setVar('searchStmt',$searchStmt);
        $view->setVar('categorie', $designationCategorie);
        return $view;
    }

}



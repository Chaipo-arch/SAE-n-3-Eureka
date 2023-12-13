<?php
namespace userService;

use yasmf\HttpHelper;
use PDO;
use PDOStatement;

class UserService {

    
    public function getCategories($pdo) : PDOStatement {
        $sql ="select code_categorie, designation 
            from a_categories
            order by code_categorie";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        return $searchStmt;
    }

    public function getArticles($pdo, $codeCategorie, $designationCategorie) : PDOStatement {
        $sql = "select id_article, code_article, designation 
            from articles
            where categorie = ?
            order by code_article";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute([$codeCategorie]);
        return $searchStmt;
    }

}
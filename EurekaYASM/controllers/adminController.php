<?php
namespace controllers;

use yasmf\View;
use PDO;
use services\AdminService;

class AdminController {


    public function __construct(AdminService $adminService) {
        $this->adminService = $adminService;
    }



}

<?php
namespace yasmf;

use services\UserService;
use controllers;

class DefaultComponentFactory
{
    function __construct() {
    }

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws Exception when controller is not found
     */
    public function buildControllerByName(string $controller_name, string $qualified_name ) {
        if($controller_name == "Home") {
            return new $qualified_name(new UserService);
        } else {
            return new $qualified_name();
        }
        
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForNameException when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return new NoServiceAvailableForNameException($service_name);
    }


    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController($this->buildUserService());
        //return new HomeController();
    }

     /**
     * @return UserService
     */
    private function buildUserService(): UserService
    {
        new UserService;
            
    }


    /**
     * @return HomeController
     */
    private function buildArticlesController(): ArticlesController
    {
        //return new ArticlesController(buildUserService());
        return new ArticlesController();
    }

}
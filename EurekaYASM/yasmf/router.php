<?php
/**
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2019   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace yasmf;

use controllers;
use services\UserService;
use yasmf\DefaultComponentFactory;
class Router
{

    /**
     * @param ComponentFactory $componentFactory component factory
     */
    function __construct(DefaultComponentFactory $defaultComponentFactory) {
        $this->defaultComponentFactory = $defaultComponentFactory;
    }

    public function route($dataSource = null)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] == "Etudiant" && isset($_GET['controller']) && $_GET['controller'] == "Admin" ) {
            $_GET['controller'] = "???";
        }
        // set the controller to enrole
        $controllerName = HttpHelper::getParam('controller') ?: 'Home';
        $controllerQualifiedName = "controllers\\" . $controllerName . "Controller";
        $controller = $this->defaultComponentFactory->buildControllerByName($controllerName,$controllerQualifiedName);
        //$controller = new $controllerQualifiedName(new UserService());
        // set the action to trigger
        
        $action = HttpHelper::getParam('action') ?: 'index';
        // trigger the appropriate action and get the resulted view
        if ($dataSource != null) {
            $view = $controller->$action($dataSource->getPdo());
        } else {
            $view = $controller->$action();
        }
        // render the view
        $view->render();
    }
}


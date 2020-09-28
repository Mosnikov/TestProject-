<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

require_once ('Screen/Login/LoginController.php');
require_once('Screen/Base/BaseController.php');
require_once ('Screen/Registration/RegistrationController.php');
require_once('Screen/Home/HomeController.php');
require_once ('Core/App.php');



class MainController extends BaseController {

    function generateHTML() {

        $controller = null;

        if ($this->app->session->isAuthorized()) {
            $controller = new HomeController($this->app);
        } elseif ($this->app->router->domen() == LoginController::$domeinName) {
            $controller = new LoginController($this->app);
        } elseif ($this->app->router->domen() == RegistrationController::$domeinName) {
            $controller = new RegistrationController($this->app);
        }

        $title = $controller->title();
        $childHTML = $controller->generateHTML();
        $html = <<<HTML
<!<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>$title</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="/Resourse/styles.css">
        <link href="/Resourse/dashboard.css" rel="stylesheet"> 
    </head>
    <body>
        $childHTML
    </body>
</html>
HTML;
        return $html;
    }

}


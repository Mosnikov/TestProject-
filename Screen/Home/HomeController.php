<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once('Screen/Base/BaseController.php');
require_once('Screen/Menu/MenuController.php');

class HomeController  extends BaseController {
    const LogOutInput = "buttonId";
    public static $domeinName = "Home";

    function title() {
        return "asdasd";
    }

    function generateHTML() {
        $formParams = $this->app->router->getFormParams();
        if (array_key_exists(self::LogOutInput, $formParams) && $formParams[self::LogOutInput] != null) {
            $this->app->session->resetAuthorization();
            header( "Location: /" . LoginController::$domeinName);
            exit(0);
        }

        $childHtml = (new MenuController($this->app))->generateHTML();
   //     $logoutButtonName = self::LogOutInput;
        $html = <<<HTML
    <div>$childHtml </div>
    <div> 
        <style>
           body {
            background: #333333;
           }
        </style>
    </div>
HTML;
        return $html;
    }
}
?>



<!--<div class="container-fluid">-->
<!--    <form class="Row" method="POST">-->
<!--        <h2>Home</h2>-->
<!--        <div class="alert" role="alert"></div>-->
<!--        <input type="submit" value="Logout" class="btn btn-lg btn-primary btn-block" name="$logoutButtonName">-->
<!--    </form>-->
<!--</div>-->
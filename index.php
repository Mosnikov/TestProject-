<?php
define("INDEX_ACCESS", 1);
define('SERVER_ROOT_PATH', dirname(__FILE__), TRUE);

require_once('Configuration.php');
require_once('Helpers/Helpers.php');
require_once('Helpers/Logger.php');
require_once('Core/App.php');
require_once('Screen/Main/MainController.php');


//prinAll($_POST);
$app = new App();
$mainController = new MainController($app);

echo $mainController->generateHTML();

function prinAll ($obj) {
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
}




?>


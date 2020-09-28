<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

require_once ('Core/DB/DB.php');
require_once ('Core/Router/Router.php');
require_once ('Core/Session/Session.php');
require_once ('Core/Network/Network.php');

class App {

    public $db;
    public $router;
    public $session;
    public $network;

    public function __construct() {
        $this->db = DB::getInstance();
        $this->router = new Router();
        $this->session = new Session();
        $this->network = new Network();
    }
}
?>
<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once ('Core/App.php');

class BaseController {

    protected $app;
    public static $domeinName = "Base";

    function __construct($app) {
        $this->app = $app;
    }

    function generateHTML() {
        return "Класс ". get_class($this) ." не реализовал метод generateHTML";
    }

    function title() {
        return "";
    }

}
?>
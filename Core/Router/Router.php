<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

class Router {

    function domen() {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        return $segments[1];
    }

    function getFormParams() {
        return $_POST;
    }
}
?>
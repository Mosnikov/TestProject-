<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

Class Session {

    const UserIdKey = "SessionId";

    function __construct() {
        session_start();
    }

    function isAuthorized() {
        return $_SESSION[self::UserIdKey] != null;
    }

    function setUserId($userIdValue) {
        $_SESSION[self::UserIdKey] = $userIdValue;
    }

    function resetAuthorization() {
        $this->setUserId("");
    }
}
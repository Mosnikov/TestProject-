<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once ('Models/User.php');

class UserScheme {
    const tableName = "users";
    const ID = "id";
    const USERNAME = "username";
    const EMAIL = "email";
    const PASSWORD = "password";
}

class UserDBService {
    private $_db;

    public function __construct($dataBaseProvader) {
        $this->_db = $dataBaseProvader;
    }

    public function registration($username, $email, $password) {
        $row[UserScheme::USERNAME] = $username;
        $row[UserScheme::EMAIL] = $email;
        $row[UserScheme::PASSWORD] = md5($password);
        $userID = $this->_db->insert(UserScheme::tableName, $row);

        if (is_null($userID)) {
            return null;
        } else {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->password = md5($password);
            $user->id = $userID;
            return $user;
        }
    }

    public function auth($username, $password) {
        $userData = $this->_db->select(UserScheme::tableName, "*", [UserScheme::USERNAME => ["value" => $username, "if" => "="],
            "and" => "",
            UserScheme::PASSWORD => ["value" => md5($password), "if" => "="]]);
        $user = null;
        logDebug($userData);

        if (!is_null($userData)) {
            $user = new User($userData);
        }
        return $user;
    }
}
?>
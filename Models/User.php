<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

class User {
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct(array $userData = null) {
        $this->id = $userData[UserScheme::ID];
        $this->username = $userData[UserScheme::USERNAME];
        $this->email = $userData[UserScheme::EMAIL];
        $this->password = $userData[UserScheme::PASSWORD];
    }
}
?>
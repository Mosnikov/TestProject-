<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once('Screen/Base/BaseController.php');
require_once ('DBServices/UserDBService.php');

class RegistrationController  extends BaseController {

    const LoginInput = "username";
    const PasInput = "pass";
    const EmailInput = "email";

    public static $domeinName = "registration";



    function title() {
        return "asdasd";
    }
    function generateHTML() {
        $formParams = $this->app->router->getFormParams();
        $isAlertHidden = "hidden";

        if (array_key_exists(self::LoginInput, $formParams) && $formParams[self::LoginInput] != null &&
            array_key_exists(self::PasInput, $formParams) && $formParams[self::PasInput] != null &&
            array_key_exists(self::EmailInput,$formParams) && $formParams[self::EmailInput] != null) {

            $service = new UserDBService($this->app->db);
            $user = $service->registration($formParams[self::LoginInput], $formParams[self::EmailInput], $formParams[self::PasInput]);
            if ($user != null) {
                $this->app->session->setUserId($user->id);
                header("Location: /");
                exit(0);
            } else {
                $isAlertHidden = "";
            }
        }
        $login = LoginController::$domeinName;
        $loginInputName = self::LoginInput;
        $passInputName = self::PasInput;
        $emailInputName = self::EmailInput;
        $html = <<<HTML
    <div class="container">
        <form class="form-signin" method="POST">
            <h2>Registration</h2>
            <div class="alert alert-danger " role="alert" $isAlertHidden>Регистрация не удалась Сорян у нас Так работает БАЗАДАННЫХ</div>
            <input type="text" value="" name="$loginInputName" class="form-control" placeholder="Username" required>
            <input type="email" value="" name="$emailInputName" class="form-control" placeholder="Email" required>
            <input type="password" value="" name="$passInputName" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
            <a href="$login" class="btn btn-lg btn-primary btn-block">Login</a>
        </form>
     </div>
HTML;
        return $html;
    }
}
?>
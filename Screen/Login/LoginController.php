<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once('Screen/Base/BaseController.php');
require_once ('DBServices/UserDBService.php');
require_once ('Screen/Registration/RegistrationController.php');

class LoginController extends BaseController {

    const LoginInput = "username";
    const PasInput = "pass";
    public static $domeinName = 'Login';


    function title() {
        return "asdasd";
    }

    function generateHTML() {
        $isAlertHidden = "hidden";
        $formParams = $this->app->router->getFormParams();

        if (array_key_exists(self::LoginInput, $formParams) && $formParams[self::LoginInput] != null &&
            array_key_exists(self::PasInput, $formParams) && $formParams[self::PasInput] != null) {

            $service = new UserDBService($this->app->db);
            $user = $service->auth($formParams[self::LoginInput], $formParams[self::PasInput]);
            if ($user != null) {
                $this->app->session->setUserId($user->id);
                header("Location: /" );
                exit(0);
            } else {
                $isAlertHidden = "";
            }
        }

        $registration = RegistrationController::$domeinName;
        $loginInputName = self::LoginInput;
        $passInputName = self::PasInput;
        $html = <<<HTML
    <div class="container">
         <form class="form-signin"  method="POST">
            <h2>Login</h2>
            <div class="alert alert-danger" role="alert" $isAlertHidden>Не правильно веден ЛОГИН или ПАРОЛЬ... Повторите попытку</div>  
            <input type="text" value="" name="$loginInputName" class="form-control" placeholder="Username" required>
            <input type="password" name="$passInputName" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
            <a href="$registration" class="btn btn-lg btn-primary btn-block">Registration</a>
        </form>
    </div>   
HTML;
        return $html;
    }
}

?>
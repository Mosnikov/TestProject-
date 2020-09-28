<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);
require_once('Screen/Base/BaseController.php');
require_once ('Screen/Main/MainController.php');

class MenuController extends BaseController {


    function generateHTML()
    {
      $logoutHref = new HomeController($this->app->session->resetAuthorization());
      $obj = "Logout";

        $html = <<<HTML
<nav class="col-md-2 d-none d-md-block bg-light sidebar">                                                       
    <div class="sidebar-sticky">                                                                                  
        <ul class="nav flex-column">                                                                                
            <li class="nav-item">     
                <a class="nav-link active" href="#">                 
                    <span data-feather="home"></span>                  
                    USER
                    <span class="sr-only">(current)</span>   
                </a>                                                                                                                                                                                                              
            </li>                                                                                                     
            <li class="nav-item">                                                                                     
                <a class="nav-link" href="#">                                                                           
                    <span data-feather="file"></span>
                    PROFILE                                                                                                
                </a>                                                                                                    
            </li>                                                                                                     
            <li class="nav-item">                                                                                     
                <a class="nav-link" href="$logoutHref">                                                                           
                    <span data-feather="shopping-cart"></span>
                    LOGOUT                                                                                              
                </a>                                                                                                    
            </li>                                                                                                       
        </ul>                                                                                                                                                                                                     
    </div>                                                                                                        
</nav>  
HTML;
        return $html;
    }
}
<?php


namespace app\controllers;

use yii\web\Controller;


class TestController extends Controller{

    public $defaultAction = "my-test";
    public function actionIndex() {
        return "<h2>Hello world!!</h2>";
    }

    public function actionMytest() {
        return __METHOD__;
    }


}
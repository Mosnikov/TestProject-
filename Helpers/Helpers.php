<?php

/**
 * @cms           market
 * @author        Faust
 * @copyright     Авторское право (c) 2016, ZeroLab.
 * @since         Версия 1.0
 */

class Helpers {
    static public function is_assoc($array) {
        $isArray = is_array($array);
        $isNumericKey = true;

        if ($isArray) {
            $allKeys = array_keys($array);

            foreach ($allKeys as $key) {
                $isNumericKey = is_numeric($key);

                if (!$isNumericKey) {
                    break;
                }
            }
        }

        return $isArray && !$isNumericKey;
    }

    static public function print_array($array) {
        return print_r($array, true);
    }

    static public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
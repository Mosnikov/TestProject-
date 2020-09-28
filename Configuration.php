<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

class CFG {
    //База данных
    const DB_HOST = "localhost";
    const DB_LOGIN = "root";
    const DB_PASS = "";
    const DB_DATABASE = "practice";

    //Логер
    const DEBUG = 1;
    const LOG_LVL = 4;


    const FOPEN_READ = 'rb';
    const FOPEN_READ_WRITE = 'r+b';
    const FOPEN_WRITE_CREATE_DESTRUCTIVE = 'wb';
    const FOPEN_READ_WRITE_CREATE_DESTRUCTIVE = 'w+b';
    const FOPEN_WRITE_CREATE = 'ab';
    const FOPEN_READ_WRITE_CREATE = 'a+b';
    const FOPEN_WRITE_CREATE_STRICT = 'xb';
    const FOPEN_READ_WRITE_CREATE_STRICT = 'x+b';

    const FILE_READ_MODE = 0644;
    const FILE_WRITE_MODE = 0666;
    const DIR_READ_MODE = 0755;
    const DIR_WRITE_MODE = 0777;
}
?>

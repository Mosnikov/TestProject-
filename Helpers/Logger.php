<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

/**
 * @cms         market
 * @author        Faust
 * @copyright    Авторское право (c) 2014, ZeroLab.
 * @since        Версия 1.0
 */

final class Logger {
    const FLOG_ERROR = 0;
    const FLOG_WARNING = 1;
    const FLOG_INFO = 2;
    const FLOG_DEBUG = 3;

    private static $_instance = null;

    private $_level = Logger::FLOG_DEBUG;
    private $_filePath = 'Log/Log.txt';

    static public function getInstance($log_lvl = Logger::FLOG_DEBUG, $log_path = 'Log/Log.txt') {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($log_lvl, $log_path);
        }
        return self::$_instance;
    }

    private function __construct($lvl, $path) {
        $this->_level = $lvl;
        $this->_filePath = SERVER_ROOT_PATH . "/" . $path;
        $this->_validFile();
    }

    private function _validFile() {
        if (!file_exists($this->_filePath)) {
            $fp = fopen($this->_filePath, CFG::FOPEN_WRITE_CREATE_STRICT);

            if (!$fp) {
                echo "ПРАВА ДОСТУПА:" . substr(sprintf('%o', fileperms($this->_filePath)), -4) . "<br>";
                echo "ОШИБКА СОЗДАНИЯ ФАЙЛА:" . $this->_filePath;
                return FALSE;
            }

            fclose($fp);
            chmod($this->_filePath, CFG::FILE_WRITE_MODE);
        }
        return TRUE;
    }

    private function _getLvlDesr($lvl) {
        switch ($lvl) {
            case Logger::FLOG_ERROR:
                return "ERROR";
            case Logger::FLOG_WARNING:
                return "WARNING";
            case Logger::FLOG_INFO:
                return "INFO";
            case Logger::FLOG_DEBUG:
                return "DEBUG";
        }
    }

    public function toLog($lvl, $messeg) {
        if ($lvl <= $this->_level) {
            if (!file_exists($this->_filePath)) {
                echo "ПРАВА ДОСТУПА:" . substr(sprintf('%o', fileperms($this->_filePath)), -4) . "<br>";
                echo "ОШИБКА ОТКРЫТИЯ ФАЙЛА:" . $this->_filePath . "<br>";
                echo "ФАЙЛ НЕ СУЩЕСТВУЕТ <br>";
                return;
            }

            $fp = fopen($this->_filePath, CFG::FOPEN_WRITE_CREATE);

            if (is_array($messeg)) {
                $messeg = print_r($messeg, true);
            }

            $microSec = (int)(explode(" ", microtime())[0] * 1000);
            if (strlen($microSec) < 3) {
                $microSec = "0" . $microSec;
            } elseif (strlen($microSec) > 3) {
                $microSec = substr($microSec, 0, 3);
            }

            flock($fp, LOCK_EX);
            fwrite($fp, "\n-[" . date("d.m.y H:i:s") . ":" . $microSec . "] - [" . $this->_getLvlDesr($lvl) . "] - " . $messeg);
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
}

function logInfo($msg) {
    Logger::getInstance()->toLog(Logger::FLOG_INFO, $msg);
}

function logDebug($msg) {
    Logger::getInstance()->toLog(Logger::FLOG_DEBUG, $msg);
}

function logError($msg) {
    Logger::getInstance()->toLog(Logger::FLOG_ERROR, $msg);
}

function logWarning($msg) {
    Logger::getInstance()->toLog(Logger::FLOG_WARNING, $msg);
}
?>

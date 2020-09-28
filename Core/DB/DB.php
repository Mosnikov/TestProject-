<?php
if (!defined("INDEX_ACCESS")) exit("Нельзя запустить скрипт: " . __FILE__);

/**
 * @cms         market
 * @author        Faust
 * @copyright    Авторское право (c) 2014, ZeroLab.
 * @since        Версия 1.0
 */
class DB
{
    private static $_instance = null;
    private $_db = null;

    //Public static
    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self(CFG::DB_HOST, CFG::DB_LOGIN, CFG::DB_PASS, CFG::DB_DATABASE);
        }
        return self::$_instance;
    }

    static public function getParam($row, $paramName, $default = null)
    {
        return $row[$paramName] == null ? $default : $row[$paramName];
    }

    //Private
    private function __construct($host, $login, $pass, $db)
    {
        if (empty($host) || empty($login) || empty($db)) {
            logError("DataBase: First call method instance must contain parameters: host, user, pass, database.");
            exit();
        }

        if (empty($pass)) {
            logWarning("DataBase: Empty password is bad idea.");
        }

        @$this->_db = new mysqli($host, $login, $pass);

        if ($this->_db->connect_errno) {
            logError("DB Connecting error: " . $this->_db->connect_error);
            $this->_sendError("DB Connecting error: " . $this->_db->connect_error);
        } elseif (mysqli_connect_error()) {
            logError("DB Connecting error: " . mysqli_connect_error());
            $this->_sendError("DB Connecting error: " . mysqli_connect_error());
        }

        $this->_selectDB($db);

        logInfo("DB Connected.");
    }

    public function __destruct()
    {
        if ($this->_db != null) {
            @$this->_db->close();
            logInfo("DB Disconected");
        }
    }

    public function _selectDB($db)
    {
        if (!$this->_db->select_db($db)) {
            logError("DB Connected. Cannot use: " . $db . ". Error: " . $this->_db->error);
            die("Error");
        }
    }

    private function _query($query)
    {
        $this->_db->query("SET NAMES 'utf8'");
        $result = $this->_db->query($query);
        if (!$result) {
            return null;
            logError("SQL:" . $query);
            logError("Invalid query: (" . $this->_db->errno . ") " . $this->_db->error . " FOR SQL:" . $query);
            header("HTTP/1.0 500");
            exit();
        }
        return $result;
    }

    //Public
    public function insert($tableName, $row)
    {
        if (!Helpers::is_assoc($row)) {
            logError("Insert row must be assoc array: \n" . Helpers::print_array($row));
            return NULL;
        } else {
            $allKeys = array_keys($row);
            $allValues = array_values($row);
            $sql = "INSERT INTO `" . $tableName . "`";
            $sql .= " (" . $this->_prepareParams($allKeys) . ")";
            $sql .= " VALUES";
            $sql .= " (" . $this->_prepareParams($allValues, "'") . ")";

            logDebug("DB INSERT SQL: \"" . $sql . "\"");
            $result = $this->_query($sql);

            if (is_null($result)) {
                return null;
            } else {
                return $this->_db->insert_id;
            }
        }
    }

    public function update($tableName, $params, $where = null)
    {
//		UPDATE persondata SET age=age*2, age=age+1 where adasd = asdasd;
        if ($tableName === null || $params === null || !is_array($params)) {
            logError("Some params in UPDATE SQL is NULL");
            header("HTTP/1.0 500");
            exit();
        }

        $sql = "UPDATE " . $tableName . " SET " . $this->_prepareUpdateParams($params);

        if (count($where) > 0) {
            $sql .= " WHERE" . $this->_where($where);
        }

        logDebug("DB UPDATE SQL: \"" . $sql . "\"");
        $resultRequst = $this->_query($sql);

        $result = NULL;

        if (!is_bool($resultRequst)) {
            if ($resultRequst->num_rows == 1) {
                $result = $resultRequst->fetch_assoc();
            } elseif ($resultRequst->num_rows > 0) {
                for ($i = 0; $i < $resultRequst->num_rows; $i++) {
                    $result[] = $resultRequst->fetch_assoc();
                }
            }

            $resultRequst->free();
        } else {
            $result = $resultRequst;
        }

        return $result;
    }

    public function select($tableName, $params, $where = null, $order = null, $group = null, $escape = "`")
    {
        if ($tableName === null || $params === null || strlen($params) == 0) {
            logError("Some params in SELECT SQL is NULL");
            header("HTTP/1.0 500");
            exit();
        }

        if (is_array($params)) {
            $params = $this->_prepareParams($params, $escape);
        }

        if (is_array($tableName)) {
            $from = "";

            foreach ($tableName as $table) {
                $from .= $escape . $table . $escape . ", ";
            }

            $from = substr($from, 0, max(strlen($from) - 2, 0));
        } else {
            $from = $escape . $tableName . $escape;
        }

        $sql = "SELECT " . $params . " FROM " . $from;

        if (count($where) > 0) {
            $sql .= " WHERE" . $this->_where($where, $escape);
        }

        if (strlen($order) > 0) {
            $sql .= " ORDER BY " . $this->_db->real_escape_string($order);
        }

        if (strlen($group) > 0) {
            $sql .= " GROUP BY " . $this->_db->real_escape_string($group);
        }

        logDebug("DB SELECT SQL: \"" . $sql . "\"");
        $return = NULL;
        $resultRequst = $this->_query($sql);

        if ($resultRequst->num_rows == 1) {
            $return = $resultRequst->fetch_assoc();
        } elseif ($resultRequst->num_rows > 0) {
            for ($i = 0; $i < $resultRequst->num_rows; $i++) {
                $return[] = $resultRequst->fetch_assoc();
            }
        }

        $resultRequst->free();

        return $return;
    }

//====================================================
    public function clearMultiResult()
    {
        while ($this->_db->next_result()) {
            $this->_db->store_result();
        }
    }

    private function _where($params, $escape = "`")
    {
        $result = "";

        foreach ($params as $key => $value) {
            if (strtolower($key) === "or") {
                $result .= " OR";
            } elseif (strtolower($key) === "and") {
                $result .= " AND";
            } else {
                $result .= " " . $this->_prepareParams([$key], $escape);
                $result .= " " . $value["if"];

                if (array_key_exists("column", $value)) {
                    $result .= " " . $this->_prepareParams([$value["column"]], $escape);
                } else {
                    $result .= " " . $this->_prepareParams([$value["value"]], "'");
                }
            }
        }

        return $result;
    }

    private function _prepareUpdateParams($params, $escapeKey = "`", $escapeValue = "`")
    {
        $result = "";

        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $result .= " " . $escapeKey . $key . $escapeKey . " = " . $escapeValue . $this->_db->real_escape_string($value) . $escapeValue . ",";
            } elseif (is_int($value)) {
                $result .= " " . $escapeKey . $key . $escapeKey . " = " . $value . ",";
            } elseif (is_null($value)) {
                $result .= " " . $escapeKey . $key . $escapeKey . " = NULL,";
            }
        }

        if (strlen($result) > 0) {
            $result = substr($result, 1, max(strlen($result) - 2, 0));
        }

        return $result;
    }

    private function _prepareParams($params, $escape = "`")
    {
        $result = "";

        foreach ($params as $param) {
            if (is_string($param)) {
                $result .= " " . $escape . $this->_db->real_escape_string($param) . $escape . ",";
            } elseif (is_int($param)) {
                $result .= " " . $param . ",";
            } elseif (is_null($param)) {
                $result .= " NULL,";
            }
        }

        if (strlen($result) > 0) {
            $result = substr($result, 1, max(strlen($result) - 2, 0));
        }

        return $result;
    }

    private function _sendError($msg)
    {
        echo json_encode(["description" => $msg]);
        header("HTTP/1.0 500");
        exit();
    }


}


?>

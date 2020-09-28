<?php

abstract class Collection implements Iterator, Serializable {
    private $var = array();

    public function add($obj) {
        array_push($this->var, $obj);
    }

    public function __construct() {
    }

    public function count() {
        return count($this->var);
    }

    public function rewind() {
        reset($this->var);
    }

    public function current() {
        $var = current($this->var);
        return $var;
    }

    public function key() {
        $var = key($this->var);
        return $var;
    }

    public function next() {
        $var = next($this->var);
        return $var;
    }

    public function valid() {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    public function firstObj() {
        if ($this->count() > 0) {
            reset($this->var);
            return $this->current();
        }
        return NULL;
    }

    public function lastObj() {
        if ($this->count() > 0) {
            end($this->var);
            return $this->current();
        }
        return NULL;
    }
}

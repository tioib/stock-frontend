<?php

abstract class Template
{
    protected $conn;
    protected $query;
    protected $table;

    public $id;

    public function fulltable()
    {
        $this->table = $this->conn->query($this->query);
    }
    public function fetch()
    {
        return $this->table->fetchAll(PDO::FETCH_ASSOC);
    }
    public abstract function fetchFull($id);
    public abstract function create($value);
    public abstract function update($value);
    public abstract function delete();
}

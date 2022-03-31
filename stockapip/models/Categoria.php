<?php
require "Template.php";

class Categoria extends Template
{
    const DEFAULT_QUERY = "SELECT eqc_IdClasifEquipo as id, eqc_Descripcion as descripcion FROM EquiposClasificacion";

    public function __construct($db)
    {
        $this->conn = $db;
        $this->query = self::DEFAULT_QUERY;
    }

    public function create($value)
    {
        $this->table = $this->conn->query("SELECT MAX(eqc_IdClasifEquipo) as valor FROM EquiposClasificacion");
        $id = intval($this->table->fetch(PDO::FETCH_ASSOC)["valor"])+1;

        $this->query = "INSERT INTO EquiposClasificacion (eqc_IdClasifEquipo,eqc_Descripcion) VALUES (?,?)";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$id,$value]);

        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function update($value)
    {
        $this->query = "UPDATE EquiposClasificacion SET eqc_Descripcion = ? WHERE eqc_IdClasifEquipo = ?";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$value,$this->id]);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function delete()
    {
        $this->query = "DELETE FROM EquiposClasificacion WHERE eqc_IdClasifEquipo = ".$this->id;
        $result = $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function fetchFull($id)
    {
        {
            $this->query = $this->query . " WHERE eqc_IdClasifEquipo = ".$id;
            $this->table = $this->conn->query($this->query);
            $this->query = self::DEFAULT_QUERY;
            return $this->table->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function sort($select)
    {
        switch($select)
        {
            case 0: $this->query = $this->query." ORDER BY eqc_IdClasifEquipo"; break;
            case 1: $this->query = $this->query." ORDER BY eqc_IdClasifEquipo DESC"; break;

            case 2: $this->query = $this->query." ORDER BY eqc_Descripcion"; break;
            case 3: $this->query = $this->query." ORDER BY eqc_Descripcion DESC"; break;
        }

        $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($this->fetch());
    }
}

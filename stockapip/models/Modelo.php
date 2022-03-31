<?php
require "Template.php";

class Modelo extends Template
{
    const DEFAULT_QUERY = "SELECT eqm_IdModeloEquipo as id, eqm_Descripcion as descripcion FROM EquiposModelo";

    public function __construct($db)
    {
        $this->conn = $db;
        $this->query = self::DEFAULT_QUERY;
    }


    public function create($value)
    {
        $this->table = $this->conn->query("SELECT MAX(eqm_IdModeloEquipo) as valor FROM EquiposModelo");
        $id = intval($this->table->fetch(PDO::FETCH_ASSOC)["valor"])+1;

        $this->query = "INSERT INTO EquiposModelo (eqm_IdModeloEquipo,eqm_Descripcion) VALUES (?,?)";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$id,$value]);

        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function update($value)
    {
        $this->query = "UPDATE EquiposModelo SET eqm_Descripcion = ? WHERE eqm_IdModeloEquipo = ?";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$value,$this->id]);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function delete()
    {
        $this->query = "DELETE FROM EquiposModelo WHERE eqm_IdModeloEquipo = ".$this->id;
        $result = $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function fetchFull($id)
    {
        {
            $this->query = $this->query . " WHERE eqm_IdModeloEquipo = ".$id;
            $this->table = $this->conn->query($this->query);
            $this->query = self::DEFAULT_QUERY;
            return $this->table->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function sort($select)
    {
        switch($select)
        {
            case 0: $this->query = $this->query." ORDER BY eqm_IdModeloEquipo"; break;
            case 1: $this->query = $this->query." ORDER BY eqm_IdModeloEquipo DESC"; break;

            case 2: $this->query = $this->query." ORDER BY eqm_Descripcion"; break;
            case 3: $this->query = $this->query." ORDER BY eqm_Descripcion DESC"; break;
        }

        $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($this->fetch());
    }
}

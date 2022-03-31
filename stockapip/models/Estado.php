<?php
require "Template.php";

class Estado extends Template
{
    const DEFAULT_QUERY = "SELECT eqe_IdEstado as id, eqe_Descripcion as descripcion FROM EquiposEstado";

    public function __construct($db)
    {
        $this->conn = $db;
        $this->query = self::DEFAULT_QUERY;
    }

    public function create($value)
    {
        $this->table = $this->conn->query("SELECT MAX(eqe_IdEstado) as valor FROM EquiposEstado");
        $id = intval($this->table->fetch(PDO::FETCH_ASSOC)["valor"])+1;

        $this->query = "INSERT INTO EquiposEstado (eqe_IdEstado,eqe_Descripcion) VALUES (?,?)";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$id,$value]);

        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function update($value)
    {
        $this->query = "UPDATE EquiposEstado SET eqe_Descripcion = ? WHERE eqe_IdEstado = ?";
        $this->table = $this->conn->prepare($this->query);
        $result = $this->table->execute([$value,$this->id]);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function delete()
    {
        $this->query = "DELETE FROM EquiposEstado WHERE eqe_IdEstado = ".$this->id;
        $result = $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function fetchFull($id)
    {
        {
            $this->query = $this->query . " WHERE eqe_IdEstado = ".$id;
            $this->table = $this->conn->query($this->query);
            $this->query = self::DEFAULT_QUERY;
            return $this->table->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function sort($select)
    {
        switch($select)
        {
            case 0: $this->query = $this->query." ORDER BY eqe_IdEstado"; break;
            case 1: $this->query = $this->query." ORDER BY eqe_IdEstado DESC"; break;

            case 2: $this->query = $this->query." ORDER BY eqe_Descripcion"; break;
            case 3: $this->query = $this->query." ORDER BY eqe_Descripcion DESC"; break;
        }

        $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($this->fetch());
    }
}

<?php


class Equipo
{
    const DEFAULT_QUERY = "SELECT 
                            equ_IdEquipo as id, 
                            equ_NroSerie as serial, 
                            /*equ_eqc_IdClasifEquipo as id_categoria, */
                            eqc_Descripcion as categoria, 
                            eqm_Descripcion as modelo, 
                            /*equ_eqm_IdModeloEquipo as id_modelo, */
                            equ_abo_IdAbonadoActual as abonado, 
                            equ_MacAdress as mac, 
                            eqe_Descripcion as estado, 
                            /*equ_eqe_IdEstado as id_estado, */
                            equ_UltFechaDeEstado as fecha,
                            equ_InfoAdicional as info 
                           FROM 
                            Equipos 
                                full outer join EquiposClasificacion 
                                on eqc_IdClasifEquipo = equ_eqc_IdClasifEquipo 
                                    full outer join EquiposEstado 
                                    on equ_eqe_IdEstado = eqe_IdEstado 
                                        full outer join EquiposModelo 
                                        on equ_eqm_IdModeloEquipo = eqm_IdModeloEquipo";

    public $sortval;
    private $conn;
    private $table;
    public $query;

    public $sort = false;
    private $ids = array();

    public $id;

    public function __construct($db)
    {
        $this->query = self::DEFAULT_QUERY;
        $this->conn = $db;
    }

    public function fulltable()
    {
        $this->sort();
        $this->table = $this->conn->query($this->query);
    }

    public function execSort()
    {
        $this->sort();
        $this->table = $this->conn->prepare($this->query);
        $rt = $this->table->execute($this->ids);
        $this->sort = false;
        $this->ids = array();

        $this->query = self::DEFAULT_QUERY;
        return $rt;
    }

    public function byCategoria($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_eqc_IdClasifEquipo = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_eqc_IdClasifEquipo = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;
    }

    public function byModelo($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_eqm_IdModeloEquipo = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_eqm_IdModeloEquipo = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;
    }

    public function byEstado($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_eqe_IdEstado = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_eqe_IdEstado = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;

    }

    public function byAbonado($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_abo_IdAbonadoActual = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_abo_IdAbonadoActual = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;
    }

    public function byMac($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_MacAdress = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_MacAdress = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;
    }

    public function bySerial($id)
    {
        if($this->sort)
        {
            $this->query = $this->query . " AND equ_NroSerie = ?";
        }
        else
        {
            $this->query = $this->query . " WHERE equ_NroSerie = ?";
            $this->sort = true;
        }
        $this->ids[] = $id;
    }

    public function fetch()
    {
        return $this->table->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchFull($id)
    {
        $this->query = "SELECT 
                            equ_IdEquipo as id, 
                            equ_NroSerie as serial, 
                            equ_eqc_IdClasifEquipo as id_categoria,
                            eqc_Descripcion as categoria, 
                            eqm_Descripcion as modelo, 
                            equ_eqm_IdModeloEquipo as id_modelo, 
                            equ_abo_IdAbonadoActual as abonado, 
                            equ_MacAdress as mac, 
                            eqe_Descripcion as estado, 
                            equ_eqe_IdEstado as id_estado, 
                            equ_UltFechaDeEstado as fecha,
                            equ_InfoAdicional as info 
                           FROM 
                            Equipos 
                                full outer join EquiposClasificacion 
                                on eqc_IdClasifEquipo = equ_eqc_IdClasifEquipo 
                                    full outer join EquiposEstado 
                                    on equ_eqe_IdEstado = eqe_IdEstado 
                                        full outer join EquiposModelo 
                                        on equ_eqm_IdModeloEquipo = eqm_IdModeloEquipo
                                WHERE
                                     equ_IdEquipo = ".$id;
        $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return $this->table->fetch(PDO::FETCH_ASSOC);

    }

    public function updateCategoria($id_categoria)
    {
        $this->query = "UPDATE Equipos SET equ_eqc_IdClasifEquipo = ".$id_categoria." WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function updateModelo($id_modelo)
    {
        $this->query = "UPDATE Equipos SET equ_eqm_IdModeloEquipo = ".$id_modelo." WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function updateSerial($nroSerie)
    {
        $this->query = "UPDATE Equipos SET equ_NroSerie = '".$nroSerie."' WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function updateEstado($estado)
    {
        echo "UPDATE Equipos SET equ_eqe_IdEstado = ".$estado.", equ_UltFechaDeEstado = '".date("Ymd H:i")."' WHERE equ_IdEquipo = ".$this->id;
        $this->query = "UPDATE Equipos SET equ_eqe_IdEstado = ".$estado.", equ_UltFechaDeEstado = '".date("Ymd H:i")."' WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result); //devolver resultado
    }

    public function updateAbonado($abonado)
    {
        $this->query = "UPDATE Equipos SET equ_abo_IdAbonadoActual = ".$abonado.", equ_abo_cli_IdClienteActual = ".intdiv(intval($abonado),1000)." WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function updateMac($mac)
    {
        $this->query = "UPDATE Equipos SET equ_MacAdress = '".$mac."' WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function updateInfo($info)
    {
        $this->query = "UPDATE Equipos SET equ_InfoAdicional = '".$info."' WHERE equ_IdEquipo = ".$this->id;
        $result = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($result);
    }

    public function getLastId()
    {
        $this->table = $this->conn->query("SELECT MAX(equ_IdEquipo) as valor FROM Equipos");
        $this->id = intval($this->table->fetch(PDO::FETCH_ASSOC)["valor"])+1;
    }

    public function create($params)
    {
        $this->query = "INSERT INTO Equipos (equ_IdEquipo,
                                            equ_eqc_IdClasifEquipo,
                                            equ_eqm_IdModeloEquipo,
                                            equ_NroSerie,
                                            equ_eqe_IdEstado,
                                            equ_UltFechaDeEstado,
                                            equ_MacAdress,
                                            equ_InfoAdicional)";
        $this->query = $this->query." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->table = $this->conn->prepare($this->query);
        $rt = $this->table->execute($params);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($rt);
    }

    public function sort()
    {
        switch ($this->sortval)
        {
            case 0: $this->query = $this->query." ORDER BY equ_IdEquipo"; break;
            case 1: $this->query = $this->query." ORDER BY equ_IdEquipo DESC"; break;

            case 2: $this->query = $this->query." ORDER BY equ_NroSerie"; break;
            case 3: $this->query = $this->query." ORDER BY equ_NroSerie DESC"; break;

            case 4: $this->query = $this->query." ORDER BY eqc_Descripcion"; break;
            case 5: $this->query = $this->query." ORDER BY eqc_Descripcion DESC"; break;

            case 6: $this->query = $this->query." ORDER BY eqm_Descripcion"; break;
            case 7: $this->query = $this->query." ORDER BY eqm_Descripcion DESC"; break;

            case 8: $this->query = $this->query." ORDER BY equ_abo_IdAbonadoActual"; break;
            case 9: $this->query = $this->query." ORDER BY equ_abo_IdAbonadoActual DESC"; break;

            case 10: $this->query = $this->query." ORDER BY equ_MacAdress"; break;
            case 11: $this->query = $this->query." ORDER BY equ_MacAdress DESC"; break;

            case 12: $this->query = $this->query." ORDER BY eqe_Descripcion"; break;
            case 13: $this->query = $this->query." ORDER BY eqe_Descripcion DESC"; break;

            case 14: $this->query = $this->query." ORDER BY equ_UltFechaDeEstado"; break;
            case 15: $this->query = $this->query." ORDER BY equ_UltFechaDeEstado DESC"; break;

            case 16: $this->query = $this->query." ORDER BY equ_InfoAdicional"; break;
            case 17: $this->query = $this->query." ORDER BY equ_InfoAdicional DESC"; break;
        }
    }

    public function delete()
    {
        $this->query = "DELETE FROM Equipos WHERE equ_IdEquipo = ".$this->id;
        $return = $this->table = $this->conn->query($this->query);
        $this->query = self::DEFAULT_QUERY;
        return json_encode($return);
    }
}

<?php
header('Access-Control-Allow-Origin: *');
require "Connection.php";

$equipo = null;

if($_GET["cual"] === "equipo")
{
    require "models/Equipo.php";
    $equipo = new Equipo(Connection::Connect());
    $equipo->id = $_GET["id"];

    $return = array();

    if(isset($_GET["categoria"]) &&  $_GET["categoria"] != '') $return[] = $equipo->updateCategoria($_GET["categoria"]);
    if(isset($_GET["modelo"]) &&  $_GET["modelo"] != '') $return[] = $equipo->updateModelo($_GET["modelo"]);
    if(isset($_GET["serial"]) &&  $_GET["serial"] != '') $return[] = $equipo->updateSerial($_GET["serial"]);
    if(isset($_GET["estado"]) &&  $_GET["estado"] != '') $return[] = $equipo->updateEstado($_GET["estado"]);
    if(isset($_GET["abonado"]) &&  $_GET["abonado"] != '') $return[] = $equipo->updateAbonado($_GET["abonado"]);
    if(isset($_GET["mac"]) &&  $_GET["mac"] != '') $return[] = $equipo->updateMac($_GET["mac"]);
    if(isset($_GET["info"]) &&  $_GET["info"] != '') $return[] = $equipo->updateInfo($_GET["info"]);

    echo json_encode($return);
}
else
{
    switch ($_GET["cual"])
    {
        case "categoria":
            require "models/Categoria.php";
            $equipo = new Categoria(Connection::Connect());
            break;

        case "modelo":
            require "models/Modelo.php";
            $equipo = new Modelo(Connection::Connect());
            break;

        case "estado":
            require "models/Estado.php";
            $equipo = new Estado(Connection::Connect());
            break;
    }
    $equipo->id = $_GET["id"];
    echo $equipo->update($_GET["descripcion"]);
}

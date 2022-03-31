<?php
header('Access-Control-Allow-Origin: *');
require "Connection.php";

$equipo = null;

switch ($_GET["cual"])
{
    case "equipo":
        require "models/Equipo.php";
        $equipo = new Equipo(Connection::Connect());
        break;

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
$equipo->fulltable();
$export = $equipo->fetchFull($_GET["id"]);
echo json_encode($export);

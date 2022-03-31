<?php
header('Access-Control-Allow-Origin: *');
require "Connection.php";
$equipo = null;

if($_GET["cual"] === "equipo")
{
    if(!isset($_GET["categoria"])) $_GET["categoria"] = null;
    if(!isset($_GET["modelo"])) $_GET["modelo"] = null;
    if(!isset($_GET["serial"])) $_GET["serial"] = null;
    if(!isset($_GET["abonado"])) $_GET["abonado"] = null;
    if(!isset($_GET["estado"])) $_GET["estado"] = null;
    if(!isset($_GET["mac"])) $_GET["mac"] = null;
    if(!isset($_GET["info"])) $_GET["info"] = null;

    require "models/Equipo.php";
    $equipo = new Equipo(Connection::Connect());
    $equipo->getLastId();
    echo $equipo->create(
        array($equipo->id,
            $_GET["categoria"],
            $_GET["modelo"],
            $_GET["serial"],
            $_GET["estado"],
            date("Ymd H:i"),
            $_GET["mac"],
            $_GET["info"]
        )
    );
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
    echo $equipo->create($_GET["descripcion"]);
}

<?php
header('Access-Control-Allow-Origin: *');
require "Connection.php";
require "models/Equipo.php";

$equipo = new Equipo(Connection::Connect());
if(isset($_GET["categoria"])) if(intval($_GET["categoria"]) != "0") $equipo->byCategoria($_GET["categoria"]);
if(isset($_GET["modelo"])) if(intval($_GET["modelo"]) != "0") $equipo->byModelo($_GET["modelo"]);
if(isset($_GET["estado"])) if(intval($_GET["estado"]) != "0") $equipo->byEstado($_GET["estado"]);
if(isset($_GET["serial"])) $equipo->bySerial($_GET["serial"]);
if(isset($_GET["abonado"])) $equipo->byAbonado($_GET["abonado"]);
if(isset($_GET["mac"])) $equipo->byMac($_GET["mac"]);

if(isset($_GET["sort"])) $equipo->sortval = intval($_GET["sort"]);

if($equipo->sort) $equipo->execSort();
else $equipo->fulltable();

$export = $equipo->fetch();
foreach ($export as &$item)
    if(!is_null($item["fecha"])) $item["fecha"] = date_format(date_create_from_format('Y-m-d', explode(' ', $item["fecha"])[0]), 'd/m/Y');
echo json_encode($export);

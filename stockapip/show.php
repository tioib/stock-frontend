<?php

header('Access-Control-Allow-Origin: *');
require "Connection.php";

$equipo = null;

switch ($_GET["cual"]) {
    case "equipo":
        require "models/Equipo.php";
        $equipo = new Equipo(Connection::Connect());
        $equipo->fulltable();
        $export = $equipo->fetch();
        foreach ($export as &$item)
            if(!is_null($item["fecha"])) $item["fecha"] = date_format(date_create_from_format('Y-m-d', explode(' ', $item["fecha"])[0]), 'd/m/Y');
        echo json_encode($export);
        break;

    case "categoria":
        require "models/Categoria.php";
        $categoria = new Categoria(Connection::Connect());
        $categoria->fulltable();
        echo json_encode($categoria->fetch());
        break;

    case "modelo":
        require "models/Modelo.php";
        $modelo = new Modelo(Connection::Connect());
        $modelo->fulltable();
        echo json_encode($modelo->fetch());
        break;

    case "estado":
        require "models/Estado.php";
        $estado = new Estado(Connection::Connect());
        $estado->fulltable();
        echo json_encode($estado->fetch());
        break;
}

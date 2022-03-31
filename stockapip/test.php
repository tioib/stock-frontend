<?php

require "models/Equipo.php";
require "Connection.php";

$a = new Equipo(Connection::Connect());

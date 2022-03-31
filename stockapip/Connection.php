<?php


class Connection
{
    public static function Connect($file = "config.ini")
    {
        if (!$settings = parse_ini_file($file, TRUE))
            throw new exception("No se pudo abrir el archivo de configuración.");

        $dns = $settings["database"]["driver"].
            ":Server=".$settings["database"]["host"].
            ";Database=".$settings["database"]["name"];

       return new PDO($dns, $settings["database"]["username"], $settings["database"]["password"]/*, $options*/);
    }
}

<?php
try
{
    $pdo = new Connection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    echo "Error: ",$e->getMessage(),"\n";
    die();
}



<?php

$servidor   = "localhost";
$porta      = 5432;
$banco      = "postgres";
$usuario    = "postgres";
$senha      = "1234";

try {
    $pdo = new PDO("pgsql:host=$servidor;port=$porta;dbname=$banco", $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e) {
    echo "Falha ao conectar ao banco de dados. <br>";
    die($e->getMessage());
}
?>
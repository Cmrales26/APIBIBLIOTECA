<?php

header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Origin: *');
$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo != "GET") {
    http_response_code(400);
    $v = array("respuesta" => "Método incorrecto");
    echo json_encode($v);
    die();
}

require_once "conexion.php";

try {
    $consulta = "SELECT * FROM libro";
    $st = $con->prepare($consulta);
    $st->execute();
    $resultado = $st->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultado);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("error" => "Error al consultar libros: " . $e->getMessage()));
}

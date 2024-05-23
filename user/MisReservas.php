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

require_once "../conexion.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    try {
        $consulta = "SELECT * FROM prestamo INNER JOIN libro ON prestamo._id_libro = libro.id_libro WHERE prestamo._id = ?";
        $st = $con->prepare($consulta);
        $v = array($id);
        $r = $st->execute($v);

        $prestamo = $st->fetchAll(PDO::FETCH_ASSOC);

        if ($prestamo) {
            http_response_code(200);
            echo json_encode($prestamo);
        } else {
            http_response_code(400);
            echo json_encode(array("respuesta" => "No se encontró el préstamo se eliminó"));
            die();
        }

    } catch (Exception $e) {
        http_response_code(400);
        $v = array("" => $e->getMessage());
        echo json_encode($v);
        die();
    }

} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan Datos");
    echo json_encode($v);
    die();
}
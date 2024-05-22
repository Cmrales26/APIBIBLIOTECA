<?php
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Origin: *');
$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo != "POST") {
    http_response_code(400);
    $v = array("respuesta" => "Método incorrecto");
    echo json_encode($v);
    die();
}

require_once "../conexion.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    try {
        $consulta = "UPDATE libro SET status = 1 WHERE id_libro = ?";
        $st = $con->prepare($consulta);
        $v = array($id);
        $r = $st->execute($v);

        if ($r === TRUE) {
            echo json_encode(array("respuesta" => "Libro Desactivado"));
        } else {
            http_response_code(500);
            $v = array("respuesta" => "Error al actualizar el estatus:");
            echo json_encode($v);
        }

    } catch (Exception $e) {
        http_response_code(400);
        $v = array("respuesta" => $e->getMessage());
        echo json_encode($v);
        die();
    }

} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan Datos");
    echo json_encode($v);
    die();
}
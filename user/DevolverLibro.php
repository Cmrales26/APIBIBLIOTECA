<?php
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Origin: *');
$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo != "DELETE") {
    http_response_code(400);
    $v = array("respuesta" => "Método incorrecto");
    echo json_encode($v);
    die();
}

require_once "../conexion.php";

if (isset($_GET["id"]) && isset($_GET["lib_id"])) {
    $id_prestamo = $_GET["id"];
    $id_libro = $_GET["lib_id"];
    try {

        $consulta = "DELETE from prestamo where id_prestamo = ? ";
        $st = $con->prepare($consulta);
        $v = array($id_prestamo);
        $st->execute($v);

        $row = $st->rowCount();
        if ($row > 0) {
            echo json_encode(array("respuesta" => "Préstamo eliminado con éxito"));
            actualizarDisponiblesLibro($id_libro, $con);
        } else {
            http_response_code(400);
            echo json_encode(array("respuesta" => "No se encontró el préstamo o no se eliminó"));
            die();
        }
    } catch (Exception $e) {
        http_response_code(500);
        $v = array("respuesta" => "Error interno: " . $e->getMessage());
        echo json_encode($v);
        die();
    }
} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan Datos");
    echo json_encode($v);
    die();
}


function actualizarDisponiblesLibro($id_libro, $conexion)
{

    try {
        $consulta = "UPDATE libro SET disponibles = disponibles + 1 WHERE id_libro = ?";
        $st = $conexion->prepare($consulta);
        $v = array($id_libro);
        $r = $st->execute($v);

        if ($r === TRUE) {
            return true;
        } else {
            http_response_code(500);
            echo json_encode(array("respuesta" => "Error al actualizar disponibilidad del libro"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("respuesta" => "Error interno: " . $e->getMessage()));
    }
}

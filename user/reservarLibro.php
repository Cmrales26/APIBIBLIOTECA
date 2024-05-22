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


if (isset($_POST["id"]) && isset($_POST["book_id"])) {
    $_id = $_POST["id"];
    $_id_libro = $_POST["book_id"];
    $fecha_prestamo = date("Y-m-d");


    if (empty(trim($_id)) || empty(trim($_id_libro))) {
        http_response_code(400);
        $v = array("respuesta" => "Los campos no pueden estar vacíos");
        echo json_encode($v);
        die();
    }

    $puedeReservar = ValidarDisponible($_id_libro, $con);

    if (!$puedeReservar) {
        http_response_code(400);
        $v = array("respuesta" => "No puede reservar este libro");
        echo json_encode($v);
        die();
    }

    try {
        $consulta = "INSERT INTO prestamo (_id, _id_libro, fecha_prestamo) VALUES (?,?,?)";
        $st = $con->prepare($consulta);
        $v = array($_id, $_id_libro, $fecha_prestamo);
        $r = $st->execute($v);

        if ($r === TRUE) {
            http_response_code(200);
            echo json_encode(array("respuesta" => "Reservado con éxito"));
            actualizarDisponiblesLibro($_id_libro, $con);
        } else {
            http_response_code(500);
            $v = array("respuesta" => "Error al reservar:");
            echo json_encode($v);
        }

    } catch (Exception $e) {
        http_response_code(500);
        $v = array("respuesta" => "Error interno: " . $e->getMessage());
        echo json_encode($v);
    }

} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan Datos");
    echo json_encode($v);
    die();
}


function ValidarDisponible($id_libro, $conexion)
{
    try {
        $consulta = "SELECT disponibles FROM libro WHERE id_libro = ?";
        $st = $conexion->prepare($consulta);
        $v = array($id_libro);
        $r = $st->execute($v);

        $resultado = $st->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado) {
            if ($resultado[0]["disponibles"] <= 0) {
                return false;
            }
            return true;
        } else {
            http_response_code(500);
            $v = array("respuesta" => "No existe el libro con id $id_libro");
        }


    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("respuesta" => "Error interno: " . $e->getMessage()));
    }
}



function actualizarDisponiblesLibro($id_libro, $conexion)
{

    try {
        $consulta = "UPDATE libro SET disponibles = disponibles - 1 WHERE id_libro = ?";
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
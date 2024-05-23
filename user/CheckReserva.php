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

    if (empty(trim($_id)) || empty(trim($_id_libro))) {
        http_response_code(400);
        $v = array("respuesta" => "Los campos no pueden estar vacíos");
        echo json_encode($v);
        die();
    }
    try {
        $consulta = "SELECT * FROM prestamo WHERE _id_libro = ? AND _id = ? ";
        $st = $con->prepare($consulta);
        $v = array($_id_libro, $_id);
        $r = $st->execute($v);
        $resultado = $st->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            http_response_code(200);
            $v = array("respuesta" => true);
            echo json_encode($v);
            die();
        } else {
            http_response_code(200);
            $v = array("respuesta" => false);
            echo json_encode($v);
            die();
        }

    } catch (Exception $e) {
        http_response_code(400);
        $v = array("respuesta" => $e->getMessage());
        echo json_encode($v);
        die();
    }
} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan parámetros");
    echo json_encode($v);
    die();
}

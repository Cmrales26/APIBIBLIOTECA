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

if (isset($_POST["id"]) && isset($_POST["contraseña"])) {

    $id = $_POST["id"];
    $contraseña = $_POST["contraseña"];

    if (empty(trim($id)) || empty(trim($contraseña))) {
        http_response_code(400);
        $v = array("respuesta" => "Los campos no pueden estar vacíos");
        echo json_encode($v);
        die();
    }

    try {
        $consulta = "SELECT id, nombre, apellido, genero,correo,telefono, rol FROM usuario WHERE id = ? AND contraseña = ? AND rol <> 'admin'";
        $st = $con->prepare($consulta);
        $v = array($id, $contraseña);
        $r = $st->execute($v);

        $resultado = $st->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado) {
            http_response_code(200);
            echo json_encode(array("respuesta" => "Login", "user" => $resultado));
            die();
        } else {
            http_response_code(500);
            $v = array("respuesta" => "Usuario o contraseña incorrectas");
            echo json_encode($v);
            die();
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
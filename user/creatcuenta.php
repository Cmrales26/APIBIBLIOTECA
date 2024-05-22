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

if (
    isset($_POST["id"]) && isset($_POST["nombre"])
    && isset($_POST["apellido"]) && isset($_POST["genero"])
    && isset($_POST["correo"]) && isset($_POST["telefono"])
    && isset($_POST["contraseña"])
) {

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $genero = $_POST["genero"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $contraseña = $_POST["contraseña"];
    $rol = "usuario";

    if (
        empty(trim($id)) || empty(trim($nombre)) || empty(trim($apellido)) || empty(trim($genero)) ||
        empty(trim($correo)) || empty(trim($telefono)) || empty(trim($contraseña)) || empty(trim($rol))
    ) {
        http_response_code(400);
        $v = array("respuesta" => "Los campos no pueden estar vacíos");
        echo json_encode($v);
        die();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $v = array("respuesta" => "Correo electrónico no válido");
        echo json_encode($v);
        die();
    }
    try {
        $consulta = "INSERT INTO usuario (id,nombre,apellido,genero,correo,telefono,contraseña,rol) VALUES (?,?,?,?,?,?,?,?)";
        $st = $con->prepare($consulta);
        $v = array($id, $nombre, $apellido, $genero, $correo, $telefono, $contraseña, $rol);
        $r = $st->execute($v);

        if ($r === TRUE) {
            echo json_encode(array("respuesta" => "Registro exitoso"));
        } else {
            http_response_code(500);
            $v = array("respuesta" => "Error al registrar usuario:");
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

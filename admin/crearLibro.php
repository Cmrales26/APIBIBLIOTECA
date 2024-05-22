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
    isset($_POST["id_libro"]) && isset($_POST["titulo"])
    && isset($_POST["descripcion"]) && isset($_POST["author"])
    && isset($_POST["editorial"]) && isset($_POST["ano_publicacion"])
    && isset($_POST["disponible"]) && isset($_POST["isbn"])
) {
    $id_libro = $_POST["id_libro"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $author = $_POST["author"];
    $editorial = $_POST["editorial"];
    $ano_publicacion = $_POST["ano_publicacion"];
    $disponible = $_POST["disponible"];
    $isbn = $_POST["isbn"];


    if (
        empty(trim($id_libro)) || empty(trim($titulo)) || empty(trim($descripcion)) || empty(trim($author)) ||
        empty(trim($editorial)) || empty(trim($ano_publicacion)) || empty(trim($disponible)) || empty(trim($isbn))
    ) {
        http_response_code(400);
        $v = array("respuesta" => "Los campos no pueden estar vacíos");
        echo json_encode($v);
        die();
    }

    try {
        $consulta = "INSERT INTO  libro (id_libro, titulo, descripcion, autor, editorial, ano_publicacion, disponibles, isbn) VALUES (?,?,?,?,?,?,?,?)";

        $st = $con->prepare($consulta);
        $v = array(
            $id_libro,
            $titulo,
            $descripcion,
            $author,
            $editorial,
            $ano_publicacion,
            $disponible,
            $isbn
        );
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
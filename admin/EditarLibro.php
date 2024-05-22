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

    $consulta = "SELECT * FROM libro WHERE id_libro = ?";
    $st = $con->prepare($consulta);
    $v = array($id_libro);
    $st->execute($v);
    $result = $st->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) === 0) {
        http_response_code(400);
        $v = array("respuesta" => "El libro no existe");
        echo json_encode($v);
        die();
    }


    $consulta = "UPDATE libro SET titulo = ?, descripcion = ?, autor = ?, editorial = ?, ano_publicacion = ?, disponibles = ?, isbn = ? WHERE id_libro = ?";
    $st = $con->prepare($consulta);
    $v = array(
        $titulo,
        $descripcion,
        $author,
        $editorial,
        $ano_publicacion,
        $disponible,
        $isbn,
        $id_libro
    );

    $r = $st->execute($v);

    if ($r === TRUE) {
        echo json_encode(array("respuesta" => "Libro actualizado con éxito"));
    } else {
        http_response_code(500);
        $v = array("respuesta" => "Error al editar libro:");
        echo json_encode($v);
    }

} else {
    http_response_code(400);
    $v = array("respuesta" => "Faltan Datos");
    echo json_encode($v);
    die();
}

<?php

header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Origin: *');
$metodo = $_SERVER["REQUEST_METHOD"];

if($metodo != "GET"){
    http_response_code(400);
	$v = array("respuesta" => "Método incorrecto");
	echo json_encode($v);
	die();
}

// The admin can access to a list of users in order to see what books they have reserved

require_once "conexion.php";
$consulta = "SELECT id, nombre, apellido, genero,correo,telefono FROM usuario";
$st = $con->prepare($consulta);
$st->execute();
$resultado = $st->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
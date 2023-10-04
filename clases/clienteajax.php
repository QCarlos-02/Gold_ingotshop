<?php

require_once '../config/database.php';
require_once 'cliente_funciones.php';

$datos = [];

if(isset($_POST['action'])){
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectar();

    if($action == 'existeUsuario'){

      $datos['ok'] = usuario_existe($_POST['usuario'], $con);
    } elseif($action = 'existeEmail'){
        $datos['ok'] = email_existe($_POST['email'], $con);
    }
}

echo json_encode($datos);
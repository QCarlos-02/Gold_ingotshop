<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == ''){
    header("location: index.php");
    exit;


}

$db = new Database();
$con = $db->conectar();

echo validaToken($id , $token, $con);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
</head>
<body>
    <header>
 
  <div class="navbar navbar-expand-lg bg-dark bg-dark ">
    <div class="container">
      <a href="#" class="navbar-brand ">
        <strong>Tienda Gold Shop</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a href="#" class="nav-link active">Catalago</a>
            </li>
             <li class="nav-item">
                <a href="#" class="nav-link ">Contacto</a>
            </li>
        </ul>
        <a href="checkout.php" class="btn btn-primary">
          
          Carrito<span id="num_cart" class="badge bg-secondary"></span>
        </a>
      </div>


    </div>
  </div>
</header>
<main>|
    
    
</main>
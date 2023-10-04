<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if($orden == null || $token == null || $token != $token_session){
  header("Location: compras.php");
  exit;
}
$db = new Database();
$con = $db->conectar();


$sqlCompra = $con->prepare("SELECT id, id_transacion, fecha, total_compra FROM compra WHERE id_transacion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra =  $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d/m/Y H:i');

$sqlDetalle =$con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra= ?");
$sqlDetalle->execute([$idCompra]);


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

    <?php include 'menu.php'; ?>

<main>
    <div class="container">

       <div class="row">
        <div class="col-12 col-md-4 mt-4">
          <div class="card mb-3">
            <div class="card-header">
              <strong>Detalle de la compra</strong>
            </div>
            <div class="card-body">
              <p><strong>Fecha: </strong> <?php echo $fecha; ?></p>
              <p><strong>Orden: </strong> <?php echo $rowCompra['id_transacion']; ?></p>
              <p><strong>Total: </strong> <?php echo  MONEDA . number_format( $rowCompra['total_compra'], 2, '.', ','); ?></p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-8">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>

              <tbody>
                <?php 
                  while($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)){ 
                    $precio = $row['precio'];
                    $cantidad = $row['cantidad'];
                    $subtotal = $precio * $cantidad;
                  ?>
                    <tr>
                      <td><?php echo $row['nombre']; ?></td>
                      <td><?php echo  MONEDA . number_format($precio , 2, '.', ','); ?></td>
                      <td><?php echo $cantidad; ?></td>
                      <td><?php echo  MONEDA . number_format($subtotal , 2, '.', ',');?></td>
                    </tr>
                  <?php } ?>
              </tbody>

            </table>
          </div>
        </div>
       </div>
            
    </div>
    
</main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>
</html>
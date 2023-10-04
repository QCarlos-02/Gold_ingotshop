<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;
$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transacion, fecha, status, total_compra  FROM compra WHERE 
id_cliente =  ? ORDER BY DATE(fecha) DESC");
$sql->execute([$idCliente]);


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

       <h4>Mis compras</h4>

       <hr>
       <?php while($row = $sql->fetch(PDO::FETCH_ASSOC)){ ?>

       <div class="card mb-3 border-primary">
    <div class="card-header">
        <?php echo $row['fecha']; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title">Compra: <?php echo $row['id_transacion']; ?></h5>
        <p class="card-text">Total: <?php echo $row['total_compra']; ?></p>
        <a href="compra_detalle.php?orden=<?php echo $row['id_transacion'];?>&token=<?php echo 
        $token; ?>" class="btn btn-primary">Ver compra</a>
    </div>
    </div>
        <?php } ?>
            
    </div>
    
</main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>
</html>
<?php

require_once 'config/config.php';
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset( $_GET['id']) ? $_GET['id']:'';
$token = isset( $_GET['token']) ? $_GET['token']:'';

if($id == '' || $token == ''){
    echo 'error al cargar la informacion ';
    exit;
}else{
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN );

    if($token == $token_tmp){

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);

        if($sql->fetchColumn() > 0){

            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1
            LIMIT 1");
            $sql->execute([$id]);   
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) /100);
            $dir_imagenes = 'imagenes/productos/' .$id.'/';

            $rutaImg = $dir_imagenes . 'imagen1.jpg';

            if(!file_exists($rutaImg)){
                $rutaImg = 'imagenes/descarga.png';
            }

            $imagenes = array();
            if(file_exists($dir_imagenes)){

            
            $dir = dir($dir_imagenes);

            while(($archivo = $dir->read()) != false){
                if($archivo != 'imagen1.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'png'))){
                    $imagenes[] =  $dir_imagenes . $archivo;
                }
            }

            $dir->close();
          }

        }
    }else{
        echo 'error al cargar la informacion ';
        exit;
    }
}




?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/86dfda5775.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include 'menu.php'; ?>
<main>
    <div class="container">
       <div class="row">
       <div class="col-md-6 order-ms-1">

       <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
    
      <img src="<?php echo $rutaImg ?>" class="d-block w-100"   height="500">
    </div>
    <?php foreach($imagenes as $img) {?>
    <div class="carousel-item ">
    <img src="<?php echo $img ?>" class="d-block w-100"  height="500" >
    
   
  </div>
  <?php } ?>

  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



       
    </div>
    <div class="col-md-6 order-ms-2">
        <h2><?php echo $nombre; ?></h2>

        <?php if($descuento >0) { ?>
            <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></h2> 
            <h2><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
            <small class="text-success"><?php echo $descuento; ?> % descuento</small>
            </h2>

            <?php } else { ?>


        <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>

        <?php } ?>

        <p class="lead">
            <?php echo $descripcion; ?>
        </p>

              <div class="col-3 my-3">
              <input class="form-control" id="cantidad" name="Cantidad"
               type="number" min="1" max="10" value="1">

              </div>

        <div class= "d-grid gap-3 col-10 mx-auto">
        <a class="btn btn-primary" href="pago.php">Comprar ahora</a>

            
            <button class="btn btn-outline-primary" type="button"
             onclick="addproducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')
            ">Agregar al carrito</button>

        </div>

    </div>


       </div>
    </div>
    
</main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
     crossorigin="anonymous"></script>

     <script>
   function addproducto(id, cantidad, token){
  let url = 'clases/carrito.php';
  let formData = new FormData(); 
  formData.append('id', id);
  formData.append('cantidad', cantidad);
  formData.append('token', token);

  fetch(url, {
    method: 'POST',
    body: formData,
    mode: 'cors'
  }).then(response => response.json())
    .then(data => {
      if(data.ok){
        let elemento = document.getElementById("num_cart");
        elemento.innerHTML = data.numero;
      }
    });
}

     </script>
</body>
</html>
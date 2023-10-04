<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

$errors=[];

if(!empty($_POST)){
    $email = trim($_POST['email']);
  

    if(esNulo([$email])){
        $errors[] = "Debe llenar todo los campos";
    }

    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida";
    }


    if(count($errors) == 0){
      if(email_existe($email, $con)){
        $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios 
        INNER JOIN clientes ON usuarios.id_cliente=clientes.id
         WHERE clientes.email LIKE ? LIMIT 1");
         $sql->execute([$email]);
         $row = $sql->fetch(PDO::FETCH_ASSOC);
         $user_id = $row['id'];
         $nombres = $row['nombres'];

         $token = solicitaPassword($user_id, $con);

         if($token !== null){
          require_once 'clases/mailer.php';
          $mailer = new Mailer();

          $url = SITE_URL . '/reset_password.php?id='.$user_id .'&token='.$token;

          $asunto ="Recuperar Contraseña - Tienda Gold Shop";
          $cuerpo = "Estimado $nombres: <br> Si has solicitado el cambio de tu contraseña da click
          en el siguiente link <a href='$url'>$url</a.>";
          $cuerpo .= "<br>Si no hiciste esta solicitud puedes ignorar este correo.";
          
          if($mailer->enviarEmail($email, $asunto, $cuerpo)){
            echo "<p><b>Correo enviado</b></p>";
            echo "<p>Hemos enviado un correo electronico a la direccion $email para restablecer la contraseña</p>";

            exit;
            
        }


         }
      } else {
        $errors[] = "No existe una cuenta activa a esta direccion de correo electronico";
      }
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
    <link href="css/style.css" rel="stylesheet">
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
          
          Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart;  ?></span>
        </a>
      </div>


    </div>
  </div>
</header>
<main class="form-login m-auto pt-4">
   <h3>Recuperar contraseña</h3>

   <?php mostrar_Mensajes($errors); ?>

   <form method="post" action="recupera.php" class="row g-3" autocomplete="off">
   <div class="form-floating">
        <input class="form-control" type="email" name="email" id="email" placeholder="Correo electronico" required>
        <label for="email">Correo electronico</label>

    </div>

    <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Continuar</button>
    </div>
    <div class="col-12">
        ¿No tiene cuenta? <a href="registro.php">Registrate aqui</a>
    </div>
    </form>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
    
  </html>
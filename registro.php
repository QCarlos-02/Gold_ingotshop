<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

$errors=[];

if(!empty($_POST)){
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $cedula = trim($_POST['cedula']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$nombres, $apellidos,  $email, $telefono,  $cedula,  $usuario,  $password, $repassword ])){
        $errors[] = "Debe llenar todo los campos";
    }

    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida";
    }


    if (!empty($password) && !empty($repassword) && $password !== $repassword) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    if(usuario_existe($usuario, $con)){
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if(email_existe($email, $con)){
        $errors[] = "El correo electronico $email ya existe";
    }

    if(count($errors) == 0){


    $id = registrar_Cliente([$nombres, $apellidos, $email, $telefono, $cedula], $con);

    if($id > 0){

        require_once 'clases/mailer.php';
        $mailer = new Mailer();
        $token = generarToken();
       $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        

        $idUsuario = registra_Usuario([$usuario, $pass_hash, $token, $id], $con);
       if($idUsuario > 0){

        $url = SITE_URL . '/activa_cliente.php?id='.$id .'&token='.$token;
        //htpp://localhost/Tienda/activa_cliente.php?id=5&token=e0a7fb1631400408245f81eff0390bcf
        $asunto ="Activar cuenta - Tienda Gold Shop";
        $cuerpo = "Estiamdo $nombres: <br> para continuar con el proceso de registro es necesario dar click
        en la siguiente link <a href='$url'>Activar cuenta</a>"; 

        if($mailer->enviarEmail($email, $asunto, $cuerpo)){
            echo "para terminar el proceso de registro siga las intrucciones que le hemos enviado a la
            direccion de correo electronico $email";

            exit;
        }

       } else {
        $errors[] = "Error al registar usuario";
       }
       
      
    }   else{
        $errors[] = "Error al registar cliente";
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
<main>|
    <div class="container">
        <h2>Datos del cliente</h2>

        <?php mostrar_Mensajes($errors); ?>


        <form class="row g-3" action="registro.php" method="post" autocomplete="off">
            <div class="col-md-6">
                <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                <input type="text" name="nombres" id="nombres" class="form-control"
                requireda>
            </div>

            <div class="col-md-6">
                <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                <input type="email" name="email" id="email" class="form-control" requireda>
                <span id="validaEmail" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span>Telefono </label>
                <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="cedula"><span class="text-danger">*</span>Cedula </label>
                <input type="text" name="cedula" id="cedula" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span>Usuario </label>
                <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                <span id="validaUsuario" class="text-danger"></span>

            </div>

            <div class="col-md-6">
            <label for="password"><span class="text-danger">*</span>Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" requireda>
        </div>

            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span>Repetir contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" requireda>
            </div>
            <i><b>Nota: Los campos con asteriscos son obligatorios</b></i>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
            
    </div>
    
</main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

 <script>
    let txtUsuario = document.getElementById('usuario')
    txtUsuario.addEventListener("blur", function(){
        existeUsuario(txtUsuario.value)
    }, false)


    let txtEmail = document.getElementById('email')
    txtEmail.addEventListener("blur", function(){
        existeEmail(txtEmail.value)
    }, false)



    function existeUsuario(usuario){
        let url="clases/clienteajax.php"
        let formData = new FormData()
        formData.append("action", "existeUsuario")
        formData.append("usuario", usuario)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                document.getElementById('usuario').value = ''
                document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
            } else{
                document.getElementById('validaUsuario').innerHTML = ''
            }
        })
    }





    function existeEmail(email){
        let url="clases/clienteajax.php"
        let formData = new FormData()
        formData.append("action", "existeEmail")
        formData.append("email", email)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                document.getElementById('email').value = ''
                document.getElementById('validaEmail').innerHTML = 'email no disponible'
            } else{
                document.getElementById('validaEmail').innerHTML = ''
            }
        })
    }
 </script>

</body>
</html>
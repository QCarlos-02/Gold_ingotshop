<?php

$path = dirname(__FILE__);



require_once $path.'/database.php';
require_once $path.'/../admin/clases/cifrado.php';


$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado =  $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach($datos as $dato){
    $config[$dato['nombre']] = $dato['valor'];
}


define("SITE_URL", "http://localhost/Tienda");
define("CLIENT_ID","AQ9RKoo1kfKrLo3fOMa_mdNfpp1QTmrXl2R3AdO1_LpJety0e1Vife_0IIUWiZv34HtR-hC3QoT8ecNS");
define("KEY_TOKEN","APR.wqc-354*");
define("MONEDA","$");

define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS", decifrar($config['correo_password']));
define("MAIL_PORT", $config['correo_puerto']);





session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>
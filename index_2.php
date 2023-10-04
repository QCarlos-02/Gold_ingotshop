<?php
require_once 'config/config.php';
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id_transacion = isset($_GET['key']) ? $_GET['key'] : '';

$error = '';
if ($id_transacion == '') {
    $error = 'error al procesar la petición';
} else {
    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transacion=? AND status=?");
    $sql->execute([$id_transacion, 'COMPLETED']);

    if ($sql->fetchColumn() > 0) {

        $sql = $con->prepare("SELECT id, fecha, email, total_compra FROM compra WHERE id_transacion=? AND status=?
        LIMIT 1");
        $sql->execute([$id_transacion, 'COMPLETED']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idcompra = $row['id'];
        $total_compra = $row['total_compra'];
        $fecha = $row['fecha'];

        $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra= ?");
        $sqlDet->execute([$idcompra]);
    } else {
        $error = 'error al comprobar la compra';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
          <script src="https://kit.fontawesome.com/86dfda5775.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include 'menu.php'; ?>
<main>
    <div class="container">

        <?php if (strlen($error) > 0) { ?>

            <div class="row">
                <div class="col">
                    <h3><?php echo $error; ?></h3>
                </div>
            </div>

        <?php } else { ?>

            <div class="row">
                <div class="col">
                    <b>Folio de la compra:</b><?php echo $id_transacion; ?><br>
                    <b>Fecha de compra:</b><?php echo $fecha; ?><br>
                    <b>Total:</b><?php echo MONEDA . number_format($total_compra, 2, '.', ','); ?><br>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Producto</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                            $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                            <tr>
                                <td><?php echo $row_det['cantidad']; ?></td>
                                <td><?php echo $row_det['nombre']; ?></td>
                                <td><?php echo $importe; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php } ?>
    </div>
</main>
</body>
</html>

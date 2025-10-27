<?php
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
include 'codigos/conexion.inc';
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$item = $_GET['item'];
$type = $_GET['type'];
$ruta = "C:\\myboxusers\\" . $_SESSION["usuario"] . '\\' . ($folder ? $folder . '/' : '') . $item;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['share_with'])) {
    $share_with = trim($_POST['share_with']);
    $sql = "SELECT usuario FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conex, $sql);
    mysqli_stmt_bind_param($stmt, 's', $share_with);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $sql = "INSERT INTO shares (owner, item_name, item_type, shared_with) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conex, $sql);
        $owner = $_SESSION["usuario"];
        mysqli_stmt_bind_param($stmt, 'ssss', $owner, $item, $type, $share_with);
        mysqli_stmt_execute($stmt);
        echo "Compartido con éxito.";
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Compartir</title>
</head>
<body class="container cuerpo">
    <header class="row">
        <?php include_once('partes/menu.inc'); ?>
    </header>
    <main class="row">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Compartir <?php echo $item; ?></strong></div>
            <div class="panel-body">
                <form method="post">
                    <fieldset>
                        <label>Compartir con usuario:</label>
                        <input type="text" name="share_with" required>
                        <input type="submit" value="Compartir">
                    </fieldset>
                </form>
            </div>
        </div>
    </main>
    <?php include_once('partes/final.inc'); ?>
</body>
</html>
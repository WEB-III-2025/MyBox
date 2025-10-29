<?php
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}

require_once('codigos/conexion.inc');

$item = $_GET['item'] ?? '';
$type = $_GET['type'] ?? '';

if ($item === '' || !in_array($type, ['file', 'folder'])) {
    echo "Parámetros inválidos.";
    exit();
}

$owner = $_SESSION["usuario"];
$ruta_completa = "C:\\myboxusers\\" . $owner . '\\' . $item;

if (!file_exists($ruta_completa)) {
    echo "El elemento no existe.";
    exit();
}

$mensaje = '';

//PROCESAR COMPALTIL
if (isset($_POST['share'])) {
    $shared_with = trim($_POST['shared_with']);

    //VALIDAR QUE EL USUARIO E'REAL A'TA LA MUELTE
    $stmt_check = mysqli_prepare($conex, "SELECT usuario FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt_check, 's', $shared_with);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) === 0) {
        $mensaje = '<div class="alert alert-danger">Error: El usuario <strong>' . htmlspecialchars($shared_with) . '</strong> no existe en el sistema.</div>';
    } else {
        //USUARIO EXISTE = Compalta ===
        $items_to_share = [];

        if ($type == 'folder') {
            //Compartir carpeta + todos los archivos dentro (recursivo) 
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ruta_completa, RecursiveDirectoryIterator::SKIP_DOTS));
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $relative_path = substr($file->getPathname(), strlen($ruta_completa) + 1);
                    $items_to_share[] = ['name' => $item . '/' . $relative_path, 'type' => 'file'];
                }
            }
            $items_to_share[] = ['name' => $item, 'type' => 'folder'];
        } else {
            $items_to_share = [['name' => $item, 'type' => 'file']];
        }

        $stmt = mysqli_prepare($conex, "INSERT INTO shares (owner, shared_with, item_name, item_type) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE owner=owner");
        foreach ($items_to_share as $share_item) {
            mysqli_stmt_bind_param($stmt, 'ssss', $owner, $shared_with, $share_item['name'], $share_item['type']);
            mysqli_stmt_execute($stmt);
        }
        $mensaje = '<div class="alert alert-success">¡Compartido exitosamente con <strong>' . htmlspecialchars($shared_with) . '</strong>!</div>';
    }
    mysqli_stmt_close($stmt_check);
}
?>

<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Compartir <?= htmlspecialchars($item) ?></title>
    <style>
        .alert { margin-top: 15px; padding: 10px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body class="container cuerpo">
    <header class="row">
        <div class="row"><div class="col-lg-6"><img src="imagenes/encabe.png" width="100%"></div></div>
        <div class="row"><?php include_once('partes/menu.inc'); ?></div>
        <br>
    </header>
    <main class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Compartir: <?= htmlspecialchars($item) ?> (<?= $type == 'folder' ? 'Carpeta' : 'Archivo' ?>)</strong>
            </div>
            <div class="panel-body">
                <?php if ($mensaje) echo $mensaje; ?>

                <form method="post" action="">
                    <label><strong>Compartir con usuario:</strong></label>
                    <input type="text" name="shared_with" required placeholder="Nombre de usuario" style="width:200px;" value="<?= isset($_POST['shared_with']) ? htmlspecialchars($_POST['shared_with']) : '' ?>">
                    <input type="hidden" name="item" value="<?= htmlspecialchars($item) ?>">
                    <input type="hidden" name="type" value="<?= $type ?>">
                    <br><br>
                    <input type="submit" name="share" value="Compartir" class="btn btn-success">
                    <a href="/mybox/carpetas.php" class="btn btn-default">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
    <?php include_once('partes/final.inc'); ?>
</body>
</html>
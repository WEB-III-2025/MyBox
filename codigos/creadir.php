<?php
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
$ruta = "C:\\myboxusers\\" . $_SESSION["usuario"];
if (isset($_POST['create_folder']) && isset($_POST['new_folder'])) {
    $new_folder = trim($_POST['new_folder']);
    $sub_ruta = $ruta . '\\' . $new_folder;
    if (!file_exists($sub_ruta)) {
        if (mkdir($sub_ruta, 0700)) {
            header("Location: /mybox/carpetas.php");
        } else {
            echo "Error al crear la carpeta.";
        }
    } else {
        echo "La carpeta ya existe.";
    }
} elseif (!file_exists($ruta)) {
    if (!mkdir($ruta, 0700)) {
        echo "ERROR: NO se pudo crear directorio.<br>";
    } else {
        header("Location: /mybox/carpetas.php");
    }
} else {
    header("Location: /mybox/carpetas.php");
}
?>
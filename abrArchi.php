<?php
$archivo = $_GET['arch'];
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$ruta = "C:\\myboxusers\\" . $_SESSION["usuario"] . '\\' . ($folder ? $folder . '/' : '') . $archivo;
$file = fopen($ruta, "r");
$contenido = fread($file, filesize($ruta));
$mime = mime_content_type($ruta);
$ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
$allowed_view = ['pdf', 'jpg', 'png'];
if (in_array($ext, $allowed_view)) {
    header("Content-type: " . $mime);
    echo $contenido;
} else {
    header("Content-Disposition: attachment; filename=" . $archivo);
    header("Content-type: " . $mime);
    header("Content-length: " . filesize($ruta));
    readfile($ruta);
}
fclose($file);
?>
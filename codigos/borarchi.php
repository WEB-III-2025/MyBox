<?php
$archivo = $_GET['archi'];
session_start();
$ruta = "C:\\myboxusers\\" . $_SESSION["usuario"] . '\\' . $archivo;
echo "<h3>";
if (file_exists($ruta)) {
    if (@unlink($ruta)) {
        echo "Se ha eliminado el fichero.";
    } else {
        echo "NO se pudo eliminar el fichero.";
    }
} else {
    echo "El fichero no existe.";
}
echo "</h3>";
header("Location: /mybox/carpetas.php" . (isset($_GET['folder']) ? '?folder=' . $_GET['folder'] : ''));
exit();
?>
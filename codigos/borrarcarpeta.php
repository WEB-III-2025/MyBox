<?php
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$carpeta = $_GET['carpeta'];
$ruta = "C:\\myboxusers\\" . $_SESSION["usuario"] . '\\' . ($folder ? $folder . '/' : '') . $carpeta;
if (is_dir($ruta)) {
    function rmdir_recursive($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        rmdir_recursive($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    rmdir_recursive($ruta);
    header("Location: /mybox/carpetas.php" . ($folder ? '?folder=' . dirname($folder) : ''));
} else {
    echo "La carpeta no existe.";
}
?>
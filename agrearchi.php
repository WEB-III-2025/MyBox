<?php
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
$ruta = "d:\\myboxusers\\" . $_SESSION["usuario"];
$Accion_Formulario = $_SERVER['PHP_SELF'];

// Verificar si se envió un archivo y validar su tamaño antes de procesar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["OC_Aceptar"]) && $_POST["OC_Aceptar"] == "frmArchi") {
    if (isset($_FILES['txtArchi']) && $_FILES['txtArchi']['error'] === UPLOAD_ERR_OK) {
        $Sali = $_FILES['txtArchi']['name'];
        $Sali = str_replace(' ', '_', $Sali);
        $tamanio = $_FILES['txtArchi']['size'];

        // Validar tamaño (20 MB = 20 * 1024 * 1024 bytes)
        if ($tamanio > 20 * 1024 * 1024) {
            $mensaje = "Error: El archivo supera los 20 MB de subida. Por favor, seleccione un archivo más pequeño.";
            include 'mensaje.php'; // Asumimos que crearemos un archivo para mostrar el mensaje
            exit();
        }

        if (!file_exists($ruta)) {
            mkdir($ruta, 0700, true);
        }
        move_uploaded_file($_FILES['txtArchi']['tmp_name'], $ruta . '/' . $Sali);
        if (chmod($ruta . '/' . $Sali, 0644)) {
            header("Location: /mybox/carpetas.php");
            exit();
        } else {
            echo 'No se pudo cambiar los permisos, consulte a su administrador';
        }
    } else {
        // Manejar errores de subida (como tamaño excesivo antes de procesar)
        switch ($_FILES['txtArchi']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $mensaje = "Error: El archivo supera los 20 MB de subida. Por favor, seleccione un archivo más pequeño.";
                include 'mensaje.php';
                exit();
            case UPLOAD_ERR_NO_FILE:
                $mensaje = "Error: No se seleccionó ningún archivo.";
                include 'mensaje.php';
                exit();
            case UPLOAD_ERR_PARTIAL:
                $mensaje = "Error: El archivo se subió parcialmente. Intente de nuevo.";
                include 'mensaje.php';
                exit();
            default:
                $mensaje = "Error desconocido al subir el archivo.";
                include 'mensaje.php';
                exit();
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Agregar archivos.</title>
</head>
<body class="container cuerpo">
    <header class="row">
        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <img src="imagenes/encabe.png" alt="logo institucional" width="100%">
            </div>
        </div>
        <div class="row">
            <?php include_once('partes/menu.inc'); ?>
        </div>
        <br />
    </header>
    <main class="row">
        <div class="panel panel-primary datos1">
            <div class="panel-heading">
                <strong>Agregar archivo</strong>
            </div>
            <div class="panel-body">
                <form action="<?php echo $Accion_Formulario; ?>" method="post" enctype="multipart/form-data" name="frmArchi">
                    <fieldset>
                        <label><strong>Archivo</strong></label><input name="txtArchi" type="file" id="txtArchi" size="60" />
                        <input type="submit" name="Submit" value="Cargar" />
                    </fieldset>
                    <input type="hidden" name="OC_Aceptar" value="frmArchi" />
                </form>
            </div>
        </div>
    </main>
    <footer class="row">
    </footer>
    <?php include_once('partes/final.inc'); ?>
</body>
</html>

<?php
// Archivo mensaje.php (crea este archivo en C:\xampp\htdocs\mybox\)
if (isset($mensaje)) {
    echo '<div style="color: red; text-align: center; margin-top: 20px;">' . htmlspecialchars($mensaje) . '</div>';
}
?>
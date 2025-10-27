<?php
require_once('codigos/conexion.inc');
$Accion_Formulario = $_SERVER['PHP_SELF'];
if (isset($_POST['txtUsua']) && isset($_POST['txtContra'])) {
    $usuario = trim($_POST['txtUsua']);
    $contra = trim($_POST['txtContra']);
    // Encriptar la contraseña con sha2(256) para compararla
    $auxSql = sprintf("SELECT nombre, usuario FROM usuarios WHERE usuario = '%s' AND contra = sha2('%s', 256)", $usuario, $contra);
    $regis = mysqli_query($conex, $auxSql);
    unset($_POST['txtUsua']);
    unset($_POST['txtContra']);
    if (mysqli_num_rows($regis) > 0) {
        $tupla = mysqli_fetch_assoc($regis);
        session_start();
        $_SESSION["autenticado"] = "SI";
        $_SESSION["nombre"] = $tupla['nombre'];
        $_SESSION["usuario"] = $tupla['usuario'];
        header("Location: /mybox/carpetas.php");
    } else {
        header("Location: /mybox/errores/400.php");
        exit();
    }
}
?>
<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Ingreso al Sitio</title>
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
        <div class="panel panel-primary logueo">
            <div class="panel-heading">
                <strong>Autentificación</strong>
            </div>
            <div class="panel-body">
                <form action="<?php echo $Accion_Formulario; ?>" method="post">
                    <fieldset>
                        <label>Usuario:</label><input type="text" name="txtUsua" size="22" maxlength="15" required /><br>
                        <label>Contraseña:</label><input type="password" name="txtContra" size="22" maxlength="15" required />
                    </fieldset>
                    <input type="submit" value="Aceptar" />
                </form>
            </div>
        </div>
        <br>
        <a href="/mybox/registrar.php">Registrarse Aquí</a>
    </main>
    <footer class="row">
    </footer>
    <?php include_once('partes/final.inc'); ?>
</body>
</html>
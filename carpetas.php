<?php
require_once('codigos/conexion.inc');
session_start();
if ($_SESSION["autenticado"] != "SI") {
    header("Location: /mybox/index.php");
    exit();
}
$ruta_base = "C:\\myboxusers\\" . $_SESSION["usuario"];
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$ruta = $ruta_base . '\\' . $folder;
$datos = explode('\\', "d:\\myboxusers");
?>
<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Ingreso al Sitio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Crear Nueva Carpeta</strong>
            </div>
            <div class="panel-body">
                <form action="/mybox/codigos/creadir.php" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <label><strong>Nombre de la carpeta:</strong></label>
                        <input type="text" name="new_folder" required>
                        <input type="submit" name="create_folder" value="Crear">
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Mi Cajón de Archivos</strong>
            </div>
            <div class="panel-body">
                <?php
                $conta = 0;
                $directorio = opendir($ruta);
                echo '<a href="/mybox/carpetas.php' . ($folder ? '?folder=' . dirname($folder) : '') . '">Regresar</a><br><br>';
                echo '<a href="/mybox/agrearchi.php">' . 'Agregar archivo' . '</a>';
                echo '<br><br>';
                echo '<table class="table table-striped">';
                echo '<tr>';
                echo '<th>Icono</th>';
                echo '<th>Nombre</th>';
                echo '<th>Peso</th>';
                echo '<th>Último acceso</th>';
                echo '<th>Archivo</th>';
                echo '<th>Directorio</th>';
                echo '<th>Lectura</th>';
                echo '<th>Escritura</th>';
                echo '<th>Ejecutable</th>';
                echo '<th>Borrar</th>';
                echo '<th>Compartir</th>';
                echo '</tr>';
                while ($elem = readdir($directorio)) {
                    if ($elem != "." && $elem != "..") {
                        $full_path = $ruta . '/' . $elem;
                        if (is_dir($full_path)) {
                            echo '<tr>';
                            $icon = '<span class="glyphicon glyphicon-folder-open" style="color:#FFA500"></span>';
                            echo '<th>' . $icon . '</th>';
                            echo '<th><a href="/mybox/carpetas.php?folder=' . ($folder ? $folder . '/' : '') . $elem . '">' . $elem . '</a></th>';
                            echo '<th>N/A</th>';
                            echo '<th>' . date("d/m/y h:i:s", fileatime($full_path)) . '</th>';
                            echo '<th>1</th>';
                            echo '<th>1</th>';
                            echo '<th>' . is_readable($full_path) . '</th>';
                            echo '<th>' . is_writeable($full_path) . '</th>';
                            echo '<th>' . is_executable($full_path) . '</th>';
                            echo '<th><a href="/mybox/codigos/borrarcarpeta.php?carpeta=' . ($folder ? $folder . '/' : '') . $elem . '" onclick="return confirm(\'¿Seguro que desea borrar la carpeta ' . $elem . '?\')">Hacer</a></th>';
                            echo '<th><a href="/mybox/share.php?item=' . ($folder ? $folder . '/' : '') . $elem . '&type=folder">Compartir</a></th>';
                            echo '</tr>';
                        } else {
                            $icon = '';
                            $ext = strtolower(pathinfo($elem, PATHINFO_EXTENSION));
                            if ($ext == 'docx' || $ext == 'doc') $icon = '<span class="glyphicon glyphicon-file" style="color:#0000FF"></span>';
                            elseif ($ext == 'pdf') $icon = '<span class="glyphicon glyphicon-file" style="color:#FF0000"></span>';
                            elseif ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') $icon = '<span class="glyphicon glyphicon-picture" style="color:#00FF00"></span>';
                            else $icon = '<span class="glyphicon glyphicon-file" style="color:#808080"></span>';
                            $size_mb = number_format(filesize($full_path) / (1024 * 1024), 2) . ' MB';
                            echo '<tr>';
                            echo '<th>' . $icon . '</th>';
                            echo '<th><a href="/mybox/abrArchi.php?arch=' . ($folder ? $folder . '/' : '') . $elem . '">' . $elem . '</a></th>';
                            echo '<th>' . $size_mb . '</th>';
                            echo '<th>' . date("d/m/y h:i:s", fileatime($full_path)) . '</th>';
                            echo '<th>1</th>';
                            echo '<th>0</th>';
                            echo '<th>' . is_readable($full_path) . '</th>';
                            echo '<th>' . is_writeable($full_path) . '</th>';
                            echo '<th>' . is_executable($full_path) . '</th>';
                            echo '<th><a href="/mybox/codigos/borarchi.php?archi=' . ($folder ? $folder . '/' : '') . $elem . '" onclick="return confirm(\'¿Seguro que desea borrar ' . $elem . '?\')">Hacer</a></th>';
                            echo '<th><a href="/mybox/share.php?item=' . ($folder ? $folder . '/' : '') . $elem . '&type=file">Compartir</a></th>';
                            echo '</tr>';
                        }
                        $conta++;
                    }
                }
                echo '</table>';
                echo '<br><br>';
                closedir($directorio);
                if ($conta == 0) {
                    echo 'La carpeta del usuario se encuentra vacía';
                }
                // Mostrar archivos compartidos
                $sql = "SELECT item_name, item_type FROM shares WHERE shared_with = ?";
                $stmt = mysqli_prepare($conex, $sql);
                mysqli_stmt_bind_param($stmt, 's', $_SESSION["usuario"]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $item_name, $item_type);
                echo '<div class="panel panel-info"><div class="panel-heading"><strong>Archivos Compartidos</strong></div><div class="panel-body">';
                while (mysqli_stmt_fetch($stmt)) {
                    echo '<p>' . $item_name . ' (' . $item_type . ') - Compartido por ' . $item_type . '</p>';
                }
                echo '</div></div>';
                mysqli_stmt_close($stmt);
                ?>
            </div>
        </div>
    </main>
    <footer class="row">
    </footer>
    <?php include_once('partes/final.inc'); ?>
</body>
</html>
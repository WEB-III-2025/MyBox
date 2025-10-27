<?php
$servidor = "localhost";
$basedatos = "mybox";
$usuario = "root";
$contra = "";
error_reporting(0);
if (!$conex = mysqli_connect($servidor, $usuario, $contra)) {
    echo "<h3><font color='red'>Error: No se puede conectar al servidor de MySQL.</font></h3>" . "<hr>";
    echo "<strong>Número........:</strong> " . mysqli_connect_errno() . "<br>";
    echo "<strong>Descripción...:</strong> " . mysqli_connect_error() . "<br>";
    exit;
}
if (!mysqli_select_db($conex, $basedatos)) {
    echo "<h3><font color='red'>Error: No se puede seleccionar la base de datos.</font></h3>" . "<hr>";
    echo "<strong>Número........:</strong> " . mysqli_errno($conex) . "<br>";
    echo "<strong>Descripción...:</strong> " . mysqli_error($conex) . "<br>";
    exit;
}
?>
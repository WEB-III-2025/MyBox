<?php
// Archivo mensaje.php (crea este archivo en C:\xampp\htdocs\mybox\)
if (isset($mensaje)) {
    echo '<div style="color: red; text-align: center; margin-top: 20px;">' . htmlspecialchars($mensaje) . '</div>';
}
?>
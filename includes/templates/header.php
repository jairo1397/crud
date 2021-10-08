<?php

if (!isset($_SESSION)) {
    session_start();
}
$auth = $_SESSION['login'] ?? false;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda-videojuegos</title>
    <link rel="stylesheet" href="/build/css/app.css">
    <script src="https://kit.fontawesome.com/9a357e20ff.js" crossorigin="anonymous"></script>
</head>

<body>
    <header class="header">
        <div class="contenedor contenido-header">

            <a href="/">
                <h1>Tienda-Videojuegos</h1>
            </a>
            <div class="derecha">
                <?php if ($auth) { ?>
                    <a href="/cerrar-sesion.php">Cerrar Sesión</a>
                <?php }; ?>
            </div>
            <!--.barra-->
        </div>
    </header>
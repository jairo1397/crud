<?php

require 'includes/config/database.php';
$db = conectarDB();
// arreglo de errores
$errores = [];

//autenticar el usuario 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // evitar inyeccion sql
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    if (!$username) {
        $errores[] = "El username no es valido";
    }
    if (!$password) {
        $errores[] = "El password es obligatorio";
    }
    if (empty($errores)) {

        //revisar si el usuario existe 
        $query = "INSERT INTO administradores (username, password, estado) VALUES ('$username','$password', 1 )";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            //redireccionando usuario
            header('Location: /login.php?resultado=1');
        }
    }
}

//incluir el header
require 'includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1 class="titulo-login">Registrarse</h1>
    <div class="contenedor-login">
        <img src="./build/img/registrarse2.webp" alt="">
        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <form class="formulario" method="POST" action="">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">password</label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <input type="submit" value="Registrarse" class="boton boton-verde">
        </form>
        <a href="./login.php">Iniciar Sesión</a>
    </div>
</main>
<!-- incluir el footer -->
<?php
incluirTemplate('footer');
?>
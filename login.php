<?php

require 'includes/config/database.php';
$db = conectarDB();

$errores = [];

//autenticar el usuario 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {



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
        $query = "SELECT * FROM administradores where username = '${username}' ";
        $resultado = mysqli_query($db, $query);


        if ($resultado->num_rows) {
            //revisar si el password es correcto
            $usuario = mysqli_fetch_assoc($resultado);

            //verificar si el password es correcto
            // $auth = password_verify($password, $usuario['password']);
            if ($password == $usuario['password']) {
                session_start();
                //llenar el arreglo de la sesion   
                $_SESSION['usuario'] = $usuario['username'];
                $_SESSION['login'] = "true";
                header('Location: /admin');
            } else {
                $errores[] = "El password es incorrecto";
            }
        } else {
            $errores[] = " El usuario no existe ";
        }
    }
}
$resultado = $_GET['resultado'] ??  null;

//incluye el header
require 'includes/funciones.php';
incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1 class="titulo-login">Iniciar Sesion</h1>
    <?php if ($resultado == 1) { ?>
        <p class="alerta exito">Registrado correctamente</p>
    <?php } ?>
    <div class="contenedor-login">
        <img src="./build/img/usuario.webp" alt="">
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

            <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
        </form>
        <a href="./registrarse.php">Registrarse</a>
    </div>

</main>

<?php
incluirTemplate('footer');
?>
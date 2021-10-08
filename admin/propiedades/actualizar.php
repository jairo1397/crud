<?php

require '../../includes/funciones.php';
$auth = estaAutenticado();

if (!$auth) {
  header('Location: /');
}
// Conectar a la base de datos 
require '../../includes/config/database.php';
$db = conectarDB();

$id = $_GET["id"];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
  header("Location: /admin");
}

//consulta para poner en los campos
$consulta_a = "SELECT * FROM videojuegos WHERE id = ${id}";
$resultado_a = mysqli_query($db, $consulta_a);
$videojuego = mysqli_fetch_assoc($resultado_a);

// Arreglo con mensajes de errores
$errores = [];

$nombre = $videojuego["nombre"];
$precio = $videojuego["precio"];
$genero = $videojuego["genero"];
$imagenPropiedad = $videojuego['imagen'];

// Ejecutar el codigo despues de que el usuario envia el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //sanitizar(evitar inyeccion sql) con mysqli_real_escape_string

  $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
  $precio = mysqli_real_escape_string($db, $_POST['precio']);
  $genero = mysqli_real_escape_string($db, $_POST['genero']);



  $imagen = $_FILES['imagen'];


  if (!$nombre) {
    $errores[] = "Debes añadir un nombre";
  }
  if (!$precio) {
    $errores[] = "EL precio es obligatorio";
  }
  if (!$genero) {
    $errores[] = "El genero del videojuego es obligatorio";
  }

  // if(!$imagen['name']){
  //   $errores[]="La imagen es obligatoria";
  // }

  //validar por tamaño maximo la imagne
  $medida = 1080 * 1000;

  if ($imagen['size'] > $medida) {
    $errores[] = "La imagen es muy pesada";
  }

  //revisar que el arreglo de errores este vacio
  if (empty($errores)) {

    //Subida de archivos

    //crear carpetas
    $carpetaImagenes = '../../imagenes/';
    if (!is_dir($carpetaImagenes)) {
      mkdir($carpetaImagenes);
    }

    $nombreImagen = '';

    if ($imagen['name']) {
      //eliminar la imagen previa     
      unlink($carpetaImagenes . $propiedad['imagen']);
      //Generar un nombre unico 
      $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

      //subir la imagen
      move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
    } else {
      $nombreImagen = $propiedad['imagen'];
    }




    //insertar en la base de datos
    $query =
      "UPDATE videojuegos SET nombre = '${nombre}', precio = '${precio}', genero= '${genero}', imagen = '${nombreImagen}' WHERE id = ${id}";

    // echo $query;
    // exit;

    $resultado = mysqli_query($db, $query);

    if ($resultado) {
      //redireccionando usuario
      header('Location: /admin?resultado=2');
    }
  }
}


incluirTemplate('header');

?>

<main class="contenedor seccion actualizar">
  <h1>Actualizar Datos</h1>

  <a href="/admin" class="boton boton-verde">Volver</a>


  <div class="contenedor-actualizar">
    <form class="formulario" method="post" enctype="multipart/form-data">

      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" placeholder="Titulo Videojuego" value="<?php echo $nombre; ?>">

      <label for="precio">Precio:</label>
      <input type="number" id="precio" name="precio" placeholder="Precio Videojuego" value="<?php echo $precio; ?>">

      <label for="imagen">Imagen:</label>
      <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

      <img class="imagen-actualizar" src="../../imagenes/<?php echo $imagenPropiedad; ?>">

      <label for="genero">Genero:</label>
      <input type="text" id="genero" name="genero" placeholder="Genero Videojuego" value="<?php echo $genero; ?>">
      <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
          <?php echo $error; ?>
        </div>
      <?php endforeach; ?>
      <input type="submit" value="Actualizar Videojuego" class="boton boton-verde">
    </form>
  </div>

</main>

<?php
incluirTemplate('footer');
?>
</body>

</html>
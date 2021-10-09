<?php
require '../includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
    header('Location: /');
}
//Importar la conexion
require '../includes/config/database.php';
$db = conectarDB();
//escribir el query
$query = "Select * from videojuegos";
//consultar la base de datos
$resultadoConsulta = mysqli_query($db, $query);

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $id = $_POST["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id) {
        //eliminar el archivo
        $query = "SELECT imagen FROM videojuegos WHERE id=${id}";
        $resultado = mysqli_query($db, $query);
        $videojuego = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/' . $videojuego['imagen']);
        //eliminar la propiedad
        $query = "DELETE FROM videojuegos WHERE id=${id}";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header("Location: /admin?resultado=3");
        }
    }
}
//muestra mensaje condicional
$resultado = $_GET['resultado'] ??  null;
//incluye header
incluirTemplate('header');
?>
<main class="contenedor seccion admin">
    <h1>Administrar catalogo de Videojuegos</h1>
    <!-- mostrar mensaje condicional -->
    <?php if ($resultado == 1) { ?>
        <p class="alerta exito">Registrado correctamente</p>
    <?php } elseif ($resultado == 2) { ?>
        <p class="alerta exito">Actualizado correctamente</p>
    <?php } elseif ($resultado == 3) { ?>
        <p class="alerta exito">Propiedad eliminada correctamente</p>
    <?php } ?>
    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Agregar Videojuego</a>
    <table class="propiedades">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- mostrar los resultados -->
            <?php while ($videojuego = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <tr>
                    <td><?php echo $videojuego['nombre']; ?></td>
                    <td><img src="/imagenes/<?php echo $videojuego['imagen']; ?>" class="imagen-tabla"></td>
                    <td><?php echo $videojuego['precio']; ?></td>
                    <td>
                        <form method="post" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $videojuego['id']; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="./propiedades/actualizar.php?id=<?php echo $videojuego['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
<?php
mysqli_close($db);
incluirTemplate('footer');
?>
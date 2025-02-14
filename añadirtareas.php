<!DOCTYPE html>
<html>
<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
<body>

<h3>Añadir Tareas</h3>

<div>
  <form method="POST" action="añadirtareas.php">
    <label for="nombre">Nombre de la tarea</label>
    <input type="text" id="nombre" name="nombre" placeholder="">

    <label for="descripcion">Descripción</label>
    <input type="text" id="descripcion" name="descripcion" placeholder="">
  
    <input type="submit" value="Guardar">
  </form>
</div>
<a href="tareas.php" class="button">Volver</a>
</body>
</html>

<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "curso", "Hito2T2");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    die("Debes iniciar sesión para añadir tareas.");
}

// Procesar el formulario para agregar tareas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['id'];

    if (!empty($nombre) && !empty($descripcion)) {
        $declaracion = $conexion->prepare("INSERT INTO tareas (nombre, descripcion) VALUES (?, ?)");
        $declaracion->bind_param("ss", $nombre, $descripcion);

        if ($declaracion->execute()) {
            echo '<div style="color: green;">Tarea agregada correctamente.</div>';
        } else {
            echo '<div style="color: red;">Error al agregar la tarea: ' . $declaracion->error . '</div>';
        }
        $declaracion->close();
    } else {
        echo '<div style="color: red;">Todos los campos son obligatorios.</div>';
    }
}

$conexion->close();
?>
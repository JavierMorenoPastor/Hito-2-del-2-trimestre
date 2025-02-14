<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .button {
  background-color:rgb(11, 182, 5);
  color: white;
  padding: 15px 40px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 30px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 50px;
  transition-duration: 0.4s;
  width: 10%;
}   

.button:hover {
  background-color:rgb(29, 231, 29);
  color: white;
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}

body { font-family: 'Arial', sans-serif; margin: 30px; }
        h2 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 10px; width: 300px; }
        button { background-color: #28a745; color: #fff; border: none; padding: 10px 15px; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { background-color: #f3f3f3; margin: 5px 0; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<a href="añadirtareas.php" class="button">Añadir tareas</a>
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


// Obtener las tareas del usuario
$usuario_id = $_SESSION['id'];
$declaracion = $conexion->prepare("SELECT nombre, descripcion FROM tareas WHERE id = ? ORDER BY id DESC");
$declaracion->bind_param("i", $usuario_id);
$declaracion->execute();
$resultado = $declaracion->get_result();

echo "<h2>Lista de Tareas de {$_SESSION['usuario']}</h2>";
echo '<ul>';
while ($fila = $resultado->fetch_assoc()) {
    echo "<li>{$fila['nombre']} - {$fila['descripcion']}</li>";
}
echo '</ul>';
$declaracion->close();
$conexion->close();
?>

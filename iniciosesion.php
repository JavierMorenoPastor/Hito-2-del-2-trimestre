
<?php
// Iniciar sesión
session_set_cookie_params(3600);
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "curso", "Hito2T2");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se envió el formulario
if (isset($_POST['login'])) {
    $usuario = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    // Preparar la consulta
    $declaracion = $conexion->prepare("SELECT * FROM usuarios WHERE nombreusuario = ?");
    if (!$declaracion) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $declaracion->bind_param("s", $usuario);
    $declaracion->execute();
    $resultado = $declaracion->get_result()->fetch_assoc();

    // Verificar el resultado
    if ($resultado) {
        if (password_verify($contraseña, $resultado['contraseña'])) {
            $_SESSION['id'] = $resultado['id'];
            $_SESSION['usuario'] = $resultado['nombreusuario'];

            //redirección a la página de tareas
            header("location: tareas.php");
            exit();
        } else {
            echo '<div style="color: red; text-align: center;">Contraseña incorrecta.</div>';
        }
    } else {
        echo '<div style="color: red; text-align: center;">Usuario no encontrado.</div>';
    }

    $declaracion->close();
} else {
    echo "El formulario no se envió correctamente.";
}

$conexion->close();
?>


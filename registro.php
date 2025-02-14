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

input[type=password], select {
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

.button {
  background-color: rgba(11, 182, 5);
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
  position: absolute;
  top: 40px;    
  left: 20px;
  border-radius: 30px; 
}
.button:hover {
  background-color:rgb(29, 231, 29);
  color: white;
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}

.h1 {
    font-family:Arial, Helvetica, sans-serif;
    text-align: center;
    margin-top: 50px;
}

</style>
<body>
<h1 class="h1">Registro</h1>

<a href="index.php" class="button">Volver</a>

<div>
  <form method="POST" action="registro.php">
    <label for="fnombre">Nombre de Usuario</label>
    <input type="text" id="fnombre" name="nombre" placeholder="">

    <label for="fcorreo">Correo</label>
    <input type="text" id="fcorreo" name="correo" placeholder="">

    <label for="fcontraseña">Contraseña</label>
    <input type="password" id="fcontraseña" name="contraseña" placeholder="">

    <label for="fccontraseña">Confirmar Contraseña</label>
    <input type="password" id="fccontraseña" name="ccontraseña" placeholder="">
  
    <input type="submit" value="Enviar">
  </form>
</div>

</body>
</html>

<?php

// Conexión con la base de datos
$conexion = new mysqli("localhost", "root", "curso", "Hito2T2");

// Verifica el envío del formulario html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $ccontraseña = $_POST['ccontraseña'];

    // Lanza un error si algun campo esta vacío
    if (empty($usuario) || empty($correo) || empty($contraseña)) {
        die('<div style="color: white; background-color: red; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center;">Completa todos los campos.</div>');
    }

    // Verifica que las contraseñas sean iguales
    if ($contraseña !== $ccontraseña) {
        die('<div style="color: white; background-color: red; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center;">Las contraseñas no son iguales.</div>');
    }

    // Verifica que ni el usuario ni el correo ya existan
    $declaracion = $conexion->prepare("SELECT id FROM usuarios WHERE nombreusuario = ? OR correo = ?");
    $declaracion->bind_param("ss", $usuario, $correo);
    $declaracion->execute();
    $declaracion->store_result();

    if ($declaracion->num_rows > 0) {
        die('<div style="color: white; background-color: red; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center;">El nombre de usuario o el correo electrónico ya están registrados.</div>');
    }
    $declaracion->close();

    // Encripta la contraseña
    $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

    // Inserta los datos en la base de datos
    $declaracion = $conexion->prepare("INSERT INTO usuarios (nombreusuario, correo, contraseña) VALUES (?, ?, ?)");
    $declaracion->bind_param("sss", $usuario, $correo, $contraseña_encriptada);

    // Lanza un mensaje si nos hemos registrado correctamente o si ha ocurrido algún error
    if ($declaracion->execute()) {
        echo '<div style="color: white; background-color: green; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center;">Registrado correctamente.</div>';
    } else {
        echo '<div style="color: white; background-color: red; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center;">Error al registrar: ' . $declaracion->error . '</div>';
    }

    $declaracion->close();
    $conexion->close();
}
?>

<?php
require_once("../system/config.php");
session_start();
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula_ingresada'];
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $correo=$_POST ['correo'];

    if (empty($cedula) || empty($nombre) || empty($password) || empty($confirmPassword)) {
        $mensaje = "Por favor completa todos los espacios.";
    } elseif ($password != $confirmPassword) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $hashedPassword = hash('sha256', $password);

        $query = "SELECT id_usuario, clave FROM usuario WHERE cedula = '$cedula'";
        $resultado = mysqli_query($conn, $query);
        $filasEncontradas = mysqli_num_rows($resultado);

        if ($filasEncontradas == 0) {
            $sql = "INSERT INTO usuario (cedula, nombre, clave, correo) 
        VALUES ('$cedula', '$nombre', '$hashedPassword','$correo')";
            if (mysqli_query($conn, $sql)) {
                $user_id = mysqli_insert_id($conn);
                $_SESSION['id_usuario'] = $user_id;
                header("Location: index.php");
                exit();
            } else {
                echo "Error al registrar el usuario: " . mysqli_error($conn);
            }
        } else {
            $mensaje = "Usuario ya existe.";
        }
    }
}

require_once("layout/authHeader.php");
?>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
                <h3 class="text-center font-weight-light my-4">Registro</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input name="cedula_ingresada" class="form-control" id="inputCedula" type="text" placeholder="Número de Cédula" />
                        <label for="inputCedula">Número de Cédula</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="correo" class="form-control" id="inputemail" type="text" placeholder="Correo" />
                        <label for="inputNombre">Correo electronico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="nombre" class="form-control" id="inputNombre" type="text" placeholder="Nombre Completo" />
                        <label for="inputNombre">Nombre Completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="password" class="form-control" id="inputPassword" type="password" placeholder="Contraseña" />
                        <label for="inputPassword">Contraseña</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="confirmPassword" class="form-control" id="inputConfirmPassword" type="password" placeholder="Confirmar Contraseña" />
                        <label for="inputConfirmPassword">Confirmar Contraseña</label>
                    </div>

                    <?php if ($mensaje != ''): ?>
                        <div class="alert alert-danger mt-2" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href="Login.php">Regresar al Login</a>
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="Login.php">¿Ya tienes una cuenta? ¡Inicia sesión!</a></div>
            </div>
        </div>
    </div>
</div>

<?php
require_once("layout/footer.php");
?>
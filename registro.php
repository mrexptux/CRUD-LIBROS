<?php
	
	//Llamamos a la conexion
	require 'funcs/conexion.php';
	require 'funcs/funcs.php';
	
	$errors = array();
	$registrousuario = array();

	if(!empty($_POST)){

		$nombre = $mysqli->real_escape_string($_POST['nombre']);
		$usuario = $mysqli->real_escape_string($_POST['usuario']);
		$password = $mysqli->real_escape_string($_POST['password']);
		$con_password = $mysqli->real_escape_string($_POST['con_password']);
		$email = $mysqli->real_escape_string($_POST['email']);
	
		$tipo_usuario = 2;
		

		if(es_nulo($nombre, $usuario, $password, $con_password, $email)){

			$errors[] = "Debe llenar todos los campos";

		}

		if(!is_email($email)){

			$errors[] = "Dirección de correo invalida";

		}

		if(!validar_password($password, $con_password)){

			$errors[] = "Las contraseñas no coinciden";

		}

		if(usuario_existente($email)){

			$errors[] = "El nombre de usuario $usuario ya existe";

		}

		if(email_existe($email)){

			$errors[] = "El correo electronico $email ya existe";

		}

		if(count($errors) == 0){

			$pass_hash = hash_password($password);
			$token = generar_token_session();

			$registro = registrar_usuario($usuario, $pass_hash, $nombre, $email, $token, $tipo_usuario );

			if($registro > 0){

				$registrousuario[] = "Se a registrado correctamente el usuario $usuario";

			}else{

				$errors[] = "Error al Registrar en LibroTeca";
			}
		}

	}
	
?>

<!doctype html>
<html lang="es">
	<!--Header con estilos-->
	<?php require_once('header.php'); ?>
	<body>
		<div class="limiter">
			<div class="container-login100" style="background-image: url('assets/images/bg-01.jpg');">
				<div class="wrap-login100">
					<form  id="signupform" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off" >
						<div id="signupalert" style="display:none" class="alert alert-danger">
							<p>Error:</p>
							<span></span>
						</div>
						<span class="login100-form-logo">
							<i class="zmdi zmdi-book"></i>
						</span>
						<span class="login100-form-title p-b-34 p-t-27">
							Registro Usuario LibroTeca
						</span>
						<div class="wrap-input100 validate-input" data-validate = "Enter nombre">
							<input type="text" class="input100" name="nombre" placeholder="Nombre" value="<?php if(isset($nombre)) echo $nombre; ?>" required >
							<span class="focus-input100" data-placeholder="&#xf20e;"></span>
						</div>
						<div class="wrap-input100 validate-input" data-validate = "Enter Usuario">
						<input type="text" class="input100" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
							<span class="focus-input100" data-placeholder="&#xf207;"></span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input type="password" class="input100" name="password" placeholder="Password" required>
							<span class="focus-input100" data-placeholder="&#xf191;"></span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input type="password" class="input100" name="con_password" placeholder="Confirmar Password" required>
							<span class="focus-input100" data-placeholder="&#xf191;"></span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Enter email">
						<input type="email" class="input100" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
							<span class="focus-input100" data-placeholder="&#xf159;"></span>
						</div>
						
						<div class="container-login100-form-btn">
							<button id="btn-signup" type="submit" class="login100-form-btn"><i class="icon-hand-right"></i>Registrar</button> 
						</div>
						<div class="text-center p-t-30">
							<a class="txt1" href="index.php">Iniciar Sesi&oacute;n</a>
						</div>
					</form>
					<?php echo resultado_desfavorable($errors); ?>
					<?php echo resultado_favorable($registrousuario); ?>
				</div>
			</div>
		</div>
	</body>
</html>															

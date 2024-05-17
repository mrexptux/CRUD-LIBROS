<?php
	session_start();
	require 'funcs/conexion.php';
	require 'funcs/funcs.php';

	$errors = array();

	//Verificamos la session para que en caso que tengamos session nos envie el bienvenidos.php
	if(isset($_SESSION["id_usuario"])){
		header("Location: bienvenidos.php");
	}

	if(!empty($_POST)){

		$usuario = $mysqli->real_escape_string($_POST['usuario']);
		$password = $mysqli->real_escape_string($_POST['password']);

		if(datos_vacios_login($usuario , $password)){
			$errors[] = "Debe llenar todos los campos";
		}
		$errors[] = login($usuario, $password);
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
					<form  id="loginform" class="login100-form " role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
						<span class="login100-form-logo">
							<i class="zmdi zmdi-book"></i>
						</span>
						<span class="login100-form-title p-b-34 p-t-27">
							Iniciar En LibroTeca
						</span>
						<div class="wrap-input100 validate-input" data-validate = "Enter username">
							<input id="usuario" type="text" class="input100" name="usuario" value="" placeholder="usuario o email" required>                                        
							<span class="focus-input100" data-placeholder="&#xf207;"></span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Enter password">
							<input id="password" type="password" class="input100" name="password" placeholder="password" required>
							<span class="focus-input100" data-placeholder="&#xf191;"></span>
						</div>
						<div class="container-login100-form-btn">
							<button id="btn-login" type="submit" class="login100-form-btn">Iniciar Sesi&oacute;n</a>
						</div>
						<div class="container-login100-form-btn pt-4">
							<button id="btn-login"  class="login100-form-btn "> <a class="navbar-brand" href="bienvenidos.php">¡Ver Demo libroteca!</a>
						</div>
						<div class="text-center p-t-30">
							<a class="txt1" href="registro.php">Registrate aquí</a>
						</div>
					</form>
					<?php echo resultado_desfavorable($errors); ?>
				</div>
			</div>
		</div>
		<div id="dropDownSelect1"></div>
	</body>
	
</html>						
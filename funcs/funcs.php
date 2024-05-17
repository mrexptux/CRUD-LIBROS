<?php
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	

	//Funcion para verificar si los campos estan nulos o no.
	function es_nulo($nombre, $user, $pass, $pass_con, $email){
		if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1 || strlen(trim($email)) < 1)
		{
			return true;
			} else {
			return false;
		}		
	}
	
	//Funcion para validar si es email 
	function is_email($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}
	
	//Funcion para validar la contraseña que coicidan
	function validar_password($var1, $var2)
	{
		if (strcmp($var1, $var2) !== 0){
			return false;
			} else {
			return true;
		}
	}
	

	//funcion para calcular el minimo y maximo de logintud
	function min_max($min, $max, $valor){
		if(strlen(trim($valor)) < $min)
		{
			return true;
		}
		else if(strlen(trim($valor)) > $max)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//Funcion para comprobar si el usuario existe
	function usuario_existente($usuario)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
		$stmt->bind_param("s", $usuario);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();
		
		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}


	//Funcion para obtener los libros
    function obtener_libros(){
        
        $stmt = $mysqli->prepare("SELECT * FROM libros");
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        return $stmt->rowCount();

    }
	
	//Funcion para comprobar si existe el email o no.
	function email_existe($email)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();
		
		if ($num > 0){
			return true;
			} else {
			return false;	
		}
	}
	
	//Funcion para gneerar el token de session
	function generar_token_session()
	{
		$gen = md5(uniqid(mt_rand(), false));	
		return $gen;
	}
	
	//Funcion para hashear la contraseña
	function hash_password($password) 
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}
	
	//Funcion para devolver el resultado desfavorable y mostrarlo por la vista.
	function resultado_desfavorable($errors){
		if(count($errors) > 0)
		{
			echo "<div id='error' class='alert alert-danger' role='alert'>
			<ul>";
			foreach($errors as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "</ul>";
			echo "</div>";
		}
	}

	//Funcion para devolver el resultado favorable.
	function resultado_favorable($errors){
		if(count($errors) > 0)
		{
			echo "<div id='error' class='alert alert-success' role='alert'>
			<ul>";
			foreach($errors as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "<a class='txt2' href='index.php'> Iniciar Session</a>";
			echo "</ul>";
			echo "</div>";
		}
	}
	
	//Funcion para registrar nuevo usuario
	function registrar_usuario($usuario, $pass_hash, $nombre, $email, $token, $tipo_usuario){
		
		global $mysqli;
		
		$stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, token, id_tipo) VALUES(?,?,?,?,?,?)");
		$stmt->bind_param('sssssi', $usuario, $pass_hash, $nombre, $email, $token, $tipo_usuario);
		
		if ($stmt->execute()){
			return $mysqli->insert_id;
			} else {
			return 0;	
		}		
	}
	
	//Funcion para comprobar los datos del login si estan llenos o vacios.
	function datos_vacios_login($usuario, $password){
		if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	

	//Funcion de login para poder iniciar sesssion.
	function login($usuario, $password)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT id, id_tipo, password FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
		$stmt->bind_param("ss", $usuario, $usuario);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		
		if($rows > 0) {
				
			$stmt->bind_result($id, $id_tipo, $passwd);
			$stmt->fetch();
			
			$validaPassw = password_verify($password, $passwd);
			
			if($validaPassw){
				
				ultima_sesion($id);
				$_SESSION['id_usuario'] = $id;
				$_SESSION['tipo_usuario'] = $id_tipo;
				
				header("location: bienvenidos.php");
				} else {
				
				$errors = "La contrase&ntilde;a es incorrecta";
			}
				
			} else {
			$errors = "El nombre de usuario o correo electr&oacute;nico no existe";
		}
		return $errors;
	}
	
	//Funcion para obtener la ultima session.
	function ultima_sesion($id)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("UPDATE usuarios SET last_session=NOW(), token_password='', password_request=0 WHERE id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$stmt->close();
	}
	

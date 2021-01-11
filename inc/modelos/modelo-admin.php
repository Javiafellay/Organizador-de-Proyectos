<?php

$accion = $_POST['accion'];
$password = $_POST['password'];
$usuario = $_POST['usuario'];

if ($accion === 'crear') {
	//Código para los administradores

	//Haschear los passwords
	$opciones = array(
		'cost' => 12
	);

	$hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);
	//establecemos la conexion
	include '../funciones/conexion.php';

	try {
		//realizamos la conexion en la BD
		$stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
		$stmt->bind_param('ss', $usuario, $hash_password);
		$stmt->execute();
			if($stmt->affected_rows > 0) {
            	$respuesta = array(
                'respuesta' => 'correcto',
                'id_insertado' => $stmt->insert_id,
                'tipo' => $accion
            );
        } else {
        	$respuesta = array(
        		'respuesta' => 'error'
        	);
        }
		$stmt->close();
		$conn->close();
	} catch (Exception $e) {
		//en caso de un error, tomar la exepcion
		
		$respuesta = array(
			'pass' => $e->getMessage()
	);

	}

	echo json_encode($respuesta);
}

if ($accion === 'login') {
	//Escribir el codigo donde se loguen los administradores
	//importamos la conexion
	include '../funciones/conexion.php';
	try {
		//Seleccionar el administrador de la base de datos
		$stmt = $conn->prepare("SELECT usuario, id, password FROM usuarios WHERE usuario = ?");
		$stmt->bind_param('s', $usuario);
		$stmt->execute();
		//Loguear el USUARIO
		$stmt->bind_result($nombre_usuario, $id_usuario, $pass_usuario);
		$stmt->fetch();
		if($nombre_usuario){
			//Si el usuario existe, verificar el pasword
			if(password_verify($password, $pass_usuario)){
				//Iniciamos la sesion
				session_start();
				$_SESSION['nombre'] = $usuario;
				$_SESSION['id'] = $id_usuario;
				$_SESSION['login'] = true;
				//Login Correcto
				$respuesta = array(
				'respuesta' => 'correcto',
				'nombre' => $nombre_usuario,
				'tipo' => $accion
				);
			}else {
				//Login incorrecto, enviar error
				$respuesta = array(
					'resultado' => 'Contraseña Incorrecta'
				);
			}

		} else {
			$respuesta = array(
				'error' => 'Usuario no existe'
			);
		}

		$stmt->close();
		$conn->close();
		
	} catch (Exception $e) {
		//en caso de un error, tomar la exepcion
		
		$respuesta = array(
			'pass' => $e->getMessage()
	);

	}
    echo json_encode($respuesta);
}
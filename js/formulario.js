
eventListeners();

function eventListeners() {
	document.querySelector('#formulario').addEventListener('submit', validarRegistro);
}

function validarRegistro(e) {

	e.preventDefault();

	var usuario = document.querySelector('#usuario').value,
		password = document.querySelector('#password').value;
		tipo = document.querySelector('#tipo').value;

		if (usuario === '' || password === '') {
		  // La validación Falló
                        swal({
                            title: 'Momento...',
                            text: 'Debes completar los campos',
                            type: 'error'
                        })
                    }else {
                    	//Ambos campos son correctos mandar ejecutar Ajax

                    	//Datos que se envian al servidor
                    	var datos = new FormData();
                    	datos.append('usuario', usuario);
                    	datos.append('password', password);
                    	datos.append('accion', tipo);

                    	//Crear el llamado a Ajax

                    	var xhr = new XMLHttpRequest();

                    	// abrir la conexion

                    	xhr.open('POST', 'inc/modelos/modelo-admin.php', true);

                    	//retorno de datos

                    	xhr.onload = function(){

                    		if (this.status === 200) {
                    			var respuesta = JSON.parse(xhr.responseText);

                    			console.log(respuesta);
                    			// Si la respuesta es correcta
                    			if(respuesta.respuesta === 'correcto'){
                    				//Si es un usuario nuevo
                    				if (respuesta.tipo === 'crear') {
                    					swal({
                    						title: 'Usuario Creado',
                    						text: 'El usuario se creó Correctamente',
                    						type: 'success'
                    					});
                    				} else if (respuesta.tipo === 'login'){
                    					swal({
                    						title: 'Login Correcto',
                    						text: 'Presione OK para ir a la Página Principal',
                    						type: 'success'
                    					})
                    					.then(resultado => {
                    						if(resultado.value) {
                    							window.location.href = 'index.php';
                    						}
                    					})
                    				}
                    			} else {
                    				//Hubo un error
                    				swal({
                    				title: 'Error',
                    				text: 'Hubo un error',
                    				type: 'error'
                    			})
                    		}
                    	}
                    }

                    	//Enviar la peticion

                    	xhr.send(datos);

                        }

                }
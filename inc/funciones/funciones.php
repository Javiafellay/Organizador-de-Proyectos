<?php
//Obtenemos la clase de cada pagina para que se carguen cuando estan son solicitadas
	function obtenerPaginaActual() {
		$archivo = basename($_SERVER['PHP_SELF']);
		$pagina = str_replace(".php", "", $archivo);
		return $pagina;
	}


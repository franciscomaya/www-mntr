	<html>	
		<head>
			<title> Migracion ContratacionSalud.TiposServicio </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function actualizarTiposServicio() {
				$cnx= conectar_postgres();
				$compania = $_SESSION["compania"];
				$cons= "UPDATE ContratacionSalud.TiposServicio SET compania = '$compania'";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
				
					
					
		}
		
		function migrarTiposServicio($paso) {
			actualizarTiposServicio();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla ContratacionSalud.TiposServicio </p> ";
		
		
		}
		
		
		
		/* Termina defincion de funciones */
		
		
			
		
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>

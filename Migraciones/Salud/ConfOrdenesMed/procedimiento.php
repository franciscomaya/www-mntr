	<html>	
		<head>
			<title> Migracion Salud.ConfOrdenesmED </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		
			
			function eliminarConfordenesmed() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.Confordenesmed";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function insertarConfordenesmed() {
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Salud.Confordenesmed FROM '$ruta/Migraciones/Salud/Confordenesmed/Confordenesmed.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
					
			}
			
			
			
			
		
		
		function migrarConfordenesmed($paso) {
		
			eliminarConfordenesmed();
			insertarConfordenesmed();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Confordenesmed </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>

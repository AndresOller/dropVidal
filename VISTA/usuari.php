<!DOCTYPE> 
<html lang="es"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="JQUERY/1.9.1.min.js"></script>
	<title>DROPVIDAL</title>

</head>
<body>
	
	<!--Incluimos la cabecera que tenemos creada-->
	<?php
		require("cabecera.php");
	?>
	<div id="contenido">
		<div id="cuadroIzquierda">
			<div id="imagenUser"><img src="<?PHP print($_SESSION["imagen"]); ?>"></div>	
			<div id="nombreUser"><h3><?PHP print($_SESSION["nom"]); ?> <?PHP print($_SESSION["cognoms"]); ?></h3>
			<?PHP print($_SESSION["mail"]); ?>
			
			</div>
			<div class="notituloIzquierda">
				<img src="IMAGENES/ICONOS/salir.png"> <a href="index.php?controlador=usuaris&accion=salir">Salir</a>
			</div>
			<div class="notituloIzquierda">
				<img src="IMAGENES/ICONOS/eliminarUsr.png"> <a href='index.php?controlador=usuaris&accion=bajaUser'>Darse de baja</a>
			</div>

			<div id="nombreTitIzq">Carpeta actual</div>

			<div class="notituloIzquierda">
				<img src="IMAGENES/ICONOS/newFolder.png"> <a id="nuevaCarpeta">Nueva Carpeta</a>
			</div>
			<div class="subIzquierda" id="subNuevaCarpeta">
				<form method="post" action="index.php?controlador=carpetas&accion=nuevaCarpeta">
					<input type="text" name="nomCarpeta" required>
				</form>
			</div>
			<?PHP
				if ($datosCarpeta['ID_CARPETA_FORANA'] != null){
			?>
			<div class="notituloIzquierda">
				<img src="IMAGENES/ICONOS/removeFolder.png"> <a href="index.php?controlador=carpetas&accion=eliminarCarpeta">Eliminar Carpeta</a>
			</div>
			<?PHP
				}
			?>
			<div class="notituloIzquierda">
				<img src="IMAGENES/ICONOS/newFile.png"> <a id="subirArchivos">Subir Archivos</a>
			</div>
			<div class="subIzquierda" id="subSubirArchivos">
				<form method="post" action="index.php?controlador=archivos&accion=subirArchivos" enctype="multipart/form-data">
					<input type="file" name="imagen[]" multiple required>
					<input type="submit" value="enviar">
				</form>
			</div>
		</div>
		<div id="directorioActual">
			<?PHP
				if ($datosCarpeta['ID_CARPETA_FORANA'] != null){
			?>
			<a href="index.php?controlador=carpetas&accion=entrarAtras"><img src="IMAGENES/ICONOS/atras.png"></a>
			<?PHP
				}
			?>
			<a href="index.php?controlador=carpetas&accion=entrarInicio"><img src="IMAGENES/ICONOS/home.png"></a>
			<?PHP
				print($_SESSION['ruta']);
			?>
		</div>
		<div id="moverCapa" class="moverArchiu">
					<h2>Selecciona la carpeta</h2>
					<form method="post" action="index.php?controlador=archivos&accion=mover">
						<select size="8" name="carpeta">
		<?PHP
						foreach ($todasCarpetas as $cosas =>$cosas1) {
							print("<option value='".$cosas1['ID_CARPETA']."'>" . $cosas1['NOM_CARPETA'] . "</option>");
						}
		?>
						</select><br>
						<input type="hidden" value="<?PHP echo $nomBaseArchiu; ?>" name="nomBase">
						<input id="enviarForm" type="submit" value="Enviar">
					</form>
		</div>
		<div id ="infoContenido">
			<div class="nombre" id="nombre">Nombre</div>
			<div class="tipo" id="tipo">Tipo</div>
			<div class="fecha" id="fecha">Fecha</div>
			<div class="eliminar" id="eliminar">Eliminar</div>
			<div class="mover" id="mover">Mover</div>
		</div>

		<?PHP

		$cont = 1;
		if (isset($carpetasCarpeta)){
			foreach ($carpetasCarpeta as $cosas =>$cosas1) {
				if($cont%2==0) print("<div id='contenidoCarp'>");
				else print("<div id='contenidoCarpWhite'>");
				foreach ($cosas1 as $nivel => $nivel1) {
					if($nivel == "NOM_CARPETA") print("<a href='index.php?controlador=carpetas&accion=accederCarpeta&idCarpeta=". $cosas1['ID_CARPETA']. "'><div class='nombre'> <img src='IMAGENES/ICONOS/folder.png'> " . $nivel1 . "</div></a>");
				}
				print("<div class='tipo'>Carpeta</div>");
				print("<div class='fecha'></div>");
				print("<div class='eliminar'></div>");
				print("<div class='mover'></div>");
				print("</div></a>");
				$cont++;
			}
		}
		if (isset($archivosCarpeta)){
			foreach ($archivosCarpeta as $cosas =>$cosas1) {
				if($cont%2==0) print("<div id='contenidoCarp'>");
				else print("<div id='contenidoCarpWhite'>");
				foreach ($cosas1 as $nivel => $nivel1) {
					if($nivel == "NOM_ARCHIU") print("<a href='CARPETAS/".$_SESSION['ruta']."/".$cosas1['NOM_BASE_ARCHIU']."' target='_blank'><div class='nombre'> <img src='IMAGENES/ICONOS/file.png'> " . $nivel1 . "</div></a>");
					else if($nivel =="TIPUS_ARCHIU") print("<div class='tipo'>" . $nivel1 . "</div>");
					else if($nivel =="FECHA") print("<div class='fecha'>" . $nivel1 . "</div>");
				}
				print("<div class='eliminar'><a href='index.php?controlador=archivos&accion=eliminarArchivo&nomBaseArchiu=".$cosas1['NOM_BASE_ARCHIU']."'><img src='IMAGENES/ICONOS/eliminar.png'></a></div>");
				print("<div class='mover'><a href='index.php?controlador=archivos&accion=cogerMover&archiu=".$cosas1['NOM_BASE_ARCHIU']."'><img id='moverArch' src='IMAGENES/ICONOS/mover.png'></a></div>");
				print("</div>");
				$cont++;
			}
		}
		?>

	</div>
	<!-- Scripts jquery-->
	<script>
		//Al clicar mostramos o ocultamos el form
	    $("#nuevaCarpeta").click(function () {
	      $("#subNuevaCarpeta").slideToggle("slow");
	    });

	    //Al clicar mostramos o ocultamos el form
	    $("#subirArchivos").click(function () {
	      $("#subSubirArchivos").slideToggle("slow");
	    });
	</script>
	<?PHP
		if($mover==true){
	?>
	<script>
	    //Al clicar mostramos o ocultamos el form
	   	$(document).ready(function(){
	      $("#moverCapa").slideToggle("slow");
	    });
    </script>
    <?PHP
    	}
    ?>
</body>
</html>
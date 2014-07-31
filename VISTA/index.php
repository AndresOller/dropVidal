<!DOCTYPE> 
<html lang="es"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>CIVID</title>
</head>
<body>
	<!--Incluimos la cabecera que tenemos creada-->
	<?php
		require("cabecera.php");
	?>
	<div id="contenidoIndex">
		<?php
			if(isset($error)){
		?>
		<div class="error">
		<?PHP
				print($error);
		?>
		</div>
		<?PHP
			}
		?>

		<div id="contenidoIndexDentro">
			<div class="formulario">
				<h2>Entrar</h2>
				<form  method="post" action="index.php?controlador=usuaris&accion=entrar">
					<input type="email" name="correo" placeholder="Correo" required><br>
					<input type="password" name="contrasenya" placeholder="Contraseña" required><br>
					<input type="submit" id="enviarForm" name="enviarUser" value="Entrar">
				</form>
			</div>
			<div class="formulario">
				<h2>Registrarse</h2>
				<form method="post" enctype="multipart/form-data" action="index.php?controlador=usuaris&accion=registrarse">
					<input type="email" placeholder="Correo" name="correo" required><br>
					<input type="text" placeholder="Nombre" name="nombre" required><br>
					<input type="text" placeholder="Apellidos" name="apellidos" required><br>
					<input type="file" name="imagen"><br>
					<input type="password" placeholder="Contraseña" name="contrasenya" required><br>
					<input type="submit" id="enviarForm" name="enviarUser" value="Registrarse">
				</form>
			</div>
		</div>
	</div>
</body>
</html>
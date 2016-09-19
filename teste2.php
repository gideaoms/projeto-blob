<!DOCTYPE html>
<html>
	<head>	
		<title>Arquivos Blob</title>
		<meta charset="utf-8">
	</head>
	<body>		
		<form action="teste.php" enctype="multipart/form-data" method="post">

			Nome:<input type="text" name="nm_arquivo" ><br>
			Arquivo:<input type="file" name="file"><br>

			<input type="submit" value="Enviar">
		</form>
		<a href="listagem.php">Listagem</a>
	</body>
</html>
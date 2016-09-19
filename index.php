<!DOCTYPE html>
<html>
	<head>	
		<title>Arquivos Blob</title>
		<meta charset="utf-8">
	</head>
	<body>		
		<form action="save.php" enctype="multipart/form-data" method="post">
			<?php 
				if (isset($_GET['codigo'])) {
					require('conexao.php');

					$sql = "SELECT * FROM arquivos WHERE codigo = ?";
					$stm = $conexao->prepare($sql);
					$stm->bindParam(1, $_GET['codigo']);
					$stm->execute();
					$arquivo = $stm->fetch();
				}
			?>

			<?php if (isset($_GET['codigo'])) : ?>
			ID<input type="text" name="codigo" value="<?=@$arquivo->codigo?>"><br>
			<?php endif; ?>

			Nome:<input type="text" name="nm_arquivo" value="<?=@$arquivo->nm_arquivo?>"><br>
			Descrição:<textarea name="descricao"><?=@$arquivo->descricao?></textarea><br>
			Arquivo:<input type="file" name="file"><br>

			<?php if (isset($_GET['codigo'])) : ?>
			Tipo<input type="text" value="<?=@$arquivo->tipo?>"><br>
			Tamanho<input type="text" value="<?=@$arquivo->tamanho?>"><br>
			Horário de envio<input type="text" name="dt_hr_envio" value="<?=@$arquivo->dt_hr_envio?>"><br>
			<?php endif; ?>

			<input type="submit" value="Enviar">
		</form>
		<a href="listagem.php">Listagem</a>
	</body>
</html>
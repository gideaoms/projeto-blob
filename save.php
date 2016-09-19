<?php

require('conexao.php');

require('upload.class.php');

$upload = new Upload('imagens');
$upload->image($_FILES['file']);

die;

$nomeArquivo = $_POST['nm_arquivo'];
$descricao = $_POST['descricao'];

$file_tmp  = $_FILES['file']['tmp_name'];
$file_size = $_FILES['file']['size'];
$file_type = $_FILES['file']['type'];

$binario = file_get_contents($file_tmp);

if (!isset($_POST['codigo'])) {
	$sql = "INSERT INTO arquivos  (nm_arquivo, descricao, arquivo, tipo, tamanho, dt_hr_envio) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

	$stm = $conexao->prepare($sql);
	$stm->bindParam(1, $nomeArquivo);
	$stm->bindParam(2, $descricao);
	$stm->bindParam(3, $binario);
	$stm->bindParam(4, $file_type);
	$stm->bindParam(5, $file_size);
	$stm->execute();
} else {
	$sql = "UPDATE arquivos SET nm_arquivo=?, descricao=?, arquivo=?, tipo=?, tamanho=?, dt_hr_envio=CURRENT_TIMESTAMP WHERE codigo = ?";

	$stm = $conexao->prepare($sql);
	$stm->bindParam(1, $nomeArquivo);
	$stm->bindParam(2, $descricao);
	$stm->bindParam(3, $binario);
	$stm->bindParam(4, $file_type);
	$stm->bindParam(5, $file_size);
	$stm->bindParam(6, $_POST['codigo']);
	$stm->execute();
}

header('Location: listagem.php');
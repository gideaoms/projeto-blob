<?php

require('conexao.php');

$sql = "SELECT * FROM arquivos WHERE codigo = ?";
$stm = $conexao->prepare($sql);
$stm->bindParam(1, $_GET['codigo']);
$stm->execute();
$arquivo = $stm->fetch();


header("Content-Type: {$arquivo->tipo}");
//echo base64_decode($arquivo->arquivo);
echo $arquivo->arquivo;
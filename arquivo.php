<?php

require('conexao.php');

$sql = "SELECT * FROM arquivos WHERE codigo = ?";
$stm = $conexao->prepare($sql);
$stm->bindParam(1, $_GET['codigo']);
$stm->execute();
$arquivo = $stm->fetch();

echo "<b>Código:</b> {$arquivo->codigo}<br>";
echo "<b>Nome:</b> {$arquivo->nm_arquivo}<br>";
echo "<b>Descrição:</b> {$arquivo->descricao}<br>";
echo "<b>Tipo:</b> {$arquivo->tipo}<br>";
echo "<b>Tamanho:</b> {$arquivo->tamanho}<br>";
echo "<b>Horário de Envio:</b> {$arquivo->dt_hr_envio}<br>";
echo "<b>Imagem:</b> <img src='mostra.php?codigo=$arquivo->codigo' alt='{$arquivo->nm_arquivo}'><br>";
echo "<a href='listagem.php'>Listagem</a>";
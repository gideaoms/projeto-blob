<meta charset="utf-8">

<?php

require('conexao.php');

$sql = "SELECT * FROM arquivos";
$resultset = $conexao->query($sql);

while ($arquivo = $resultset->fetch()) {
	echo "Arquivo: {$arquivo->nm_arquivo} | Descrição: {$arquivo->descricao} | Tipo: {$arquivo->tipo} | Tamanho: {$arquivo->tamanho} | <a href='arquivo.php?codigo={$arquivo->codigo}'>Ver Arquivo</a><a href='index.php?codigo={$arquivo->codigo}'>Editar</a><br>"; 
}

echo "<a href='index.php'>Novo</a>";
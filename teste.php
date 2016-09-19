<meta charset="utf-8">

<?php

require('redimensionar.php');

$rFor64 = new RedimensionarFor64($_FILES['file'], 100, 'imgs/');
if ($rFor64->getError()) {
	echo 'Erro: ' . $rFor64->getResult();
} else {
	echo 'funcionou: ' . base64_encode($rFor64->getResult());
}
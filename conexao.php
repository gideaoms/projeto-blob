<?php

	$conexao = new \PDO("mysql:host=localhost;dbname=projeto_blob;charset=utf8", "root", "123654", [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	$conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	$conexao->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

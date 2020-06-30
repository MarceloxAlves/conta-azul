<?php
require_once 'ContaAzulService.php';

$contaAzul = new ContaAzulService();

$contaAzul->auth("http://avantitecnologias.com.br/conta-azul/index.php");
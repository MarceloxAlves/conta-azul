<?php
function dd($param)
{
    var_dump($param);
    die();
}

require_once 'ContaAzulService.php';

$contaAzul = new ContaAzulService();

$contaAzul->auth("http://avantitecnologias.com.br/conta-azul/");
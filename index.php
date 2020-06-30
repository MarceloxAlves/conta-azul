<?php
function dd($param)
{
    var_dump($param);
    die();
}

require_once 'ContaAzulService.php';

$contaAzul = new ContaAzulService();

var_dump($contaAzul->getToken("http://localhost:8000"));
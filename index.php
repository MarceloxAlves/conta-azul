<?php
function dd($param)
{
    var_dump($param);
    die();
}

require_once 'ContaAzulService.php';

$contaAzul = new ContaAzulService();

if ($_GET['code']){
    $_SESSION['token_conta_azul'] = $_GET['code'];
}
<?php
session_start();
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

if (isset($_SESSION['token_conta_azul'])){
    echo "Logado";
}else{
    echo "<a href='./login.php'>Solicitar Permissão Conta Azul</a>";
}
<?php
session_start();
require_once 'Conexao.php';
require_once 'helper.php';
require_once 'ContaAzulService.php';
try {
    $contaAzul = new ContaAzulService();

    if ($_GET['code']) {
        $_SESSION['token_conta_azul'] = $_GET['code'];
    }

    if (isset($_SESSION['token_conta_azul'])) {
        if (!$_SESSION['access_token']) {
            $token = $contaAzul->getToken($_SESSION['token_conta_azul']);
            $contaAzul->saveSessions($token);
        }

        echo "<a href='./product.php'>Integrar Vacinas</a> | <a href='./customer.php'>Ingtegrar Pacientes</a> | <a href='./sale.php'>Integrar Vendas </a>";

    } else {
        echo "<a href='./login.php'>Solicitar Permissão Conta Azul</a>";
    }
}catch (Exception $exception){
    echo $exception->getMessage();
    echo  $exception->getCode();
}








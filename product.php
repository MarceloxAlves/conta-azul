<?php
session_start();
require_once 'helper.php';
require_once 'Conexao.php';
require_once 'ContaAzulService.php';

if ($_SESSION['access_token']) {
    $contaAzul = new ContaAzulService();
    $token = $contaAzul->refreshToken();
    $contaAzul->saveSessions($token);
    var_dump($_SESSION['access_token']);

    $vacinas = Conexao::readSQL("select * from vacina where conta_azul_id is null");
    foreach ($vacinas as $vacina) {

        $product = [
            'name' => $vacina['nome'],
            'value' => $vacina['valor'],
            'cost' => $vacina['valor'],
            "code" => $vacina["codigo"],
            "available_stock" => 10000,
        ];

        $product = $contaAzul->createProduct($product);

        var_dump($product);
        echo "</br></br>";


        Conexao::update('vacina',["conta_azul_id"=>$product->id], "WHERE codigo = :codigo", "codigo=".$vacina["codigo"]);


    }
}

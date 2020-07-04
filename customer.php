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

    $pacientes = Conexao::readSQL("select * from paciente where conta_azul_id is null");
    foreach ($pacientes as $paciente) {
        $customer = [
            'name' => $paciente['nome'],
            'email' => $paciente['email'],
            'notes' => $paciente['observacao'],
            'person_type' => "NATURAL",
            'document' => $paciente['cpf'],
            'date_of_birth' => dateContaAzul($paciente['dtnascimento']),
        ];

        $customer = $contaAzul->createCustomer($customer);
        var_dump($customer);
        echo "</br></br>";

        Conexao::update('paciente',["conta_azul_id"=>$customer->id], "WHERE codigo = :codigo", "codigo=".$paciente["codigo"]);


    }
}

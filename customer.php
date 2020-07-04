<?php
session_start();
ini_set('display_errors', 1);
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
            'email' => $paciente['email'] ? $paciente['email'] : "sememail@gmail.com",
            'notes' => "" .$paciente['observacao'],
            'person_type' => "NATURAL",
            'document' => "". $paciente['cpf'],
            'identity_document' => "".$paciente['codigo'],
            'date_of_birth' => dateContaAzul($paciente['dtnascimento']),
        ];

        $customer = $contaAzul->createCustomer($customer);

        if (!$customer){
           echo  "Não migrou: ". $paciente["nome"]." CPF: ".$paciente["cpf"];
        }else {

            Conexao::update('paciente', ["conta_azul_id" => $customer->id], "WHERE codigo = :codigo", "codigo=" . $paciente["codigo"]);
        }

    }
}

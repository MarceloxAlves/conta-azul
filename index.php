<?php
session_start();
require_once 'Conexao.php';
function dd($param)
{
    var_dump($param);
    die();
}

require_once 'ContaAzulService.php';
try {
    $contaAzul = new ContaAzulService();

    if ($_GET['code']) {
        $_SESSION['token_conta_azul'] = $_GET['code'];
    }

    if (isset($_SESSION['token_conta_azul'])) {
        echo "Logado";
        if (!$_SESSION['access_token']) {
            $token = $contaAzul->getToken($_SESSION['token_conta_azul']);
            $contaAzul->saveSessions($token);

        }
        if ($_SESSION['access_token']) {
            $token = $contaAzul->refreshToken();
            $contaAzul->saveSessions($token);
            $applications = Conexao::readSQL("select * from aplicacao app 
where paciente != '0' and idExclusao is not null");
            $pacienteArray = array();
            foreach ($applications as $application) {
                $paciente = $application['paciente'];
                $idExclusao = $application['idExclusao'];
                $pacienteArray[$paciente][$idExclusao][] = $application;
            }

            foreach ($pacienteArray as $key => $paciente) {
                $sale = array(
                    'number' => $application['idExclusao'],
                    'emission' => date('Y-m-dTH:i:sZ'),
                    'status' => 'COMMITTED',
                    'customer_id' => $key,
                    'products' => array(),
                    'discount' => array(),
                    'payment' =>
                        array(
                            'type' => 'CASH',
                            'installments' => array(),
                        ),
                    'notes' => '',
                    'shipping_cost' => 0,
                );
                $total = 0;
                foreach ($paciente as $keyIdExclusao => $idExclusao) {

                    foreach ($idExclusao as $product) {
                        $sale["products"][] = [
                            "quantity" => $product["dose"],
                            "product_id" => $product["vacina"],
                            "value" => $product["valorDose"],
                        ];
                    }

                    $receitas = Conexao::readSQL("select * from receitas rc where idExclusao = '$keyIdExclusao'");
                    if (count($receitas) > 1) {
                        $sale["payment"]["type"] = "TIMES";
                    }

                    $desconto = 0;

                    foreach ($receitas as $parcela => $receita) {
                        $desconto += $receita["desconto"];
                        $total += $receita["valor"];
                        $sale["payment"]["installments"][] = [
                            "number" => ++$parcela,
                            "due_date" => $receita['data_venc'],
                            "value" => $receita['valor'],
                        ];
                    }

                    $sale["discount"] = [
                        "measure_unit" => "VALUE",
                        "rate" => $desconto,
                    ];

                    $sale["shipping_cost"] = $total;
                }
                $result = $contaAzul->createSale($sale);
                var_dump($result);
            }
        }
    } else {
        echo "<a href='./login.php'>Solicitar Permissão Conta Azul</a>";
    }
}catch (Exception $exception){
    echo $exception->getMessage();
    echo  $exception->getCode();
}








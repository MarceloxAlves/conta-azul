<?php
session_start();
require_once 'helper.php';
require_once 'Conexao.php';
require_once 'ContaAzulService.php';
?>
<form action="" method="post">
    De <input type="date" name="date_start"> até <input type="date" name="date_end">
    <button type="submit" name="period">Enviar para o conta azul</button>
</form>
<?php
if ($_SESSION['access_token'] && isset($_POST['period'])) {

    $dataIni = $_POST['date_start'];
    $dataFin = $_POST['date_end'];


    $contaAzul = new ContaAzulService();
    $token = $contaAzul->refreshToken();
    $contaAzul->saveSessions($token);
    var_dump($_SESSION['access_token']);
    $applications = Conexao::readSQL("select app.*,  pc.conta_azul_id from aplicacao app 
 join receitas rc on app.idExclusao = rc.idExclusao
 join paciente pc on app.paciente = pc.codigo
where pc.conta_azul_id is not null and app.paciente != '0' and app.idExclusao is not null and rc.data_pagto >= '" . $dataIni . "' and rc.data_pagto <= '" . $dataFin . "'");
    $pacienteArray = array();
    foreach ($applications as $application) {
        $paciente = $application['paciente'];
        $idExclusao = $application['idExclusao'];
        $pacienteArray[$paciente][$idExclusao][] = $application;
    }

    foreach ($pacienteArray as $key => $paciente) {
        $sale = array(
            'number' => $application['idExclusao'],
            'emission' => dateContaAzul(date('Y-m-d')),
            'status' => 'COMMITTED',
            'customer_id' => $application['conta_azul_id'],
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
                $vacina = Conexao::readSQL("select * from vacina where codigo =  " . $product["vacina"])[0];
                $sale["products"][] = [
                    "quantity" => $product["dose"],
                    "product_id" => $vacina["conta_azul_id"],
                    "value" => $product["valorDose"],
                ];
            }
            $desconto = 0;

            $receitas = Conexao::readSQL("select * from receitas rc where idExclusao = '$keyIdExclusao' and agente = '$key' ");

            if (count($receitas) > 1) {
                $sale["payment"]["type"] = "TIMES";
                foreach ($receitas as $parcela => $receita) {
                    $desconto += $receita["desconto"];
                    $total += $receita["valor"];
                    $sale["payment"]["installments"][] = [
                        "number" => ++$parcela,
                        "due_date" => dateContaAzul($receita['data_venc']),
                        "value" => $receita['valor'],
                    ];
                }
            } else
                continue;

            $sale["discount"] = [
                "measure_unit" => "VALUE",
                "rate" => $desconto,
            ];

            $sale["shipping_cost"] = $total;
        }

        var_dump($sale);
        echo "</br></br>";


        $result = $contaAzul->createSale($sale);
        var_dump($result);
    }
}
?>

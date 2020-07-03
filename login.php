<?php
require_once 'ContaAzulService.php';

$contaAzul = new ContaAzulService();

$contaAzul->auth(ContaAzulService::REDIRECT_URI);
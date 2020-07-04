<?php
date_default_timezone_set('America/Bahia');
function dd($param)
{
    var_dump($param);
    die();
}

function dateContaAzul($date){
    $date = new DateTime($date);
    return $date->format('Y-m-d\TH:i:s.u');
}
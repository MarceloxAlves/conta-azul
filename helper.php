<?php
date_default_timezone_set('America/Bahia');
function dd($param)
{
    var_dump($param);
    die();
}

function dateContaAzul($date, $format='Y-m-d'){
    $dateTime = \DateTime::createFromFormat($format, $date);
    return $dateTime->format('Y-m-d\TH:i:s.z-v');
}
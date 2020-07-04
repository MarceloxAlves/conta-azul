<?php
function dd($param)
{
    var_dump($param);
    die();
}

function dateContaAzul($date){
    $dateTime = new DateTime($date." 00:00:00");
    $dateTime->setTimezone(new DateTimeZone('America/New_York'));
    return $dateTime->format('Y-m-d\TH:i:s.u');
}
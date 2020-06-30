<?php
require_once 'CurlService.php';


class ContaAzulService
{
    const URL = "https://api.contaazul.com/";
    const CLIENT_ID = "";

    function getToken($url_redirect = "")
    {
        $endpoint = self::URL . "auth/authorize?redirect_uri={$url_redirect}&client_id=" . self::CLIENT_ID . "&scope=sales&state=" . self::csrf();
        $result = CurlService::get($endpoint);
        return $result;
    }

    static function csrf()
    {
        return bin2hex(random_bytes(32));
    }


}
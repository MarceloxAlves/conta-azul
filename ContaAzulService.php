<?php
require_once 'CurlService.php';


class ContaAzulService
{
    const URL = "https://api.contaazul.com/";
    const CLIENT_ID = "5Ky95Cd53KtlSPSRIposSwnlsm5QnsPd";

    function auth($url_redirect = "", $scope = 'sales')
    {
        $endpoint = self::URL . "auth/authorize?redirect_uri={$url_redirect}&client_id=" . self::CLIENT_ID . "&scope={$scope}&state=" . self::csrf();
        header('Location: ' . $endpoint);
    }

    static function csrf()
    {
        if (function_exists('mcrypt_create_iv'))
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return bin2hex(openssl_random_pseudo_bytes(32));
    }


}
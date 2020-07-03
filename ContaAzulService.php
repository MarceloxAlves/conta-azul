<?php
require_once 'CurlService.php';


class ContaAzulService
{
    const URL = "https://api.contaazul.com/";
    const CLIENT_ID = "5Ky95Cd53KtlSPSRIposSwnlsm5QnsPd";
    const CLIENT_SECRET = "yer57BqiNUy960wIhzZcaavK20WNRzxL";
    const REDIRECT_URI = "http://avantitecnologias.com.br/conta-azul/index.php";

    function auth($url_redirect = "", $scope = 'sales')
    {
        $endpoint = self::URL . "auth/authorize?redirect_uri={$url_redirect}&client_id=" . self::CLIENT_ID . "&scope={$scope}&state=" . self::csrf();
        header('Location: ' . $endpoint);
    }

    function getToken($code)
    {
        $endpoint = self::URL . "oauth2/token";
        $data = [
            "grant_type" => "authorization_code",
            "redirect_uri" => self::REDIRECT_URI,
            "code" => $code,
        ];
        $authorization = base64_encode(self::CLIENT_ID . ":" . self::CLIENT_SECRET); // Prepare the authorisation token
        $header = [
            "Authorization: Basic $authorization"
        ];

        $endpoint .= "?" . http_build_query($data);
        return CurlService::post($endpoint, [], $header);
    }

    function refreshToken()
    {
        $endpoint = self::URL . "oauth2/token";
        $data = [
            "grant_type" => "refresh_token",
            "refresh_token" => $_SESSION['refresh_token'],
        ];
        $authorization = base64_encode(self::CLIENT_ID . ":" . self::CLIENT_SECRET); // Prepare the authorisation token
        $header = [
            "Authorization: Basic $authorization"
        ];

        $endpoint .= "?" . http_build_query($data);
        return CurlService::post($endpoint, [], $header);
    }

    function saveSessions($token){
        if (isset($token->access_token)) {
            $_SESSION['access_token'] = $token->access_token;
        }
        if (isset($token->refresh_token)) {
            $_SESSION['refresh_token'] = $token->refresh_token;
        }
    }

    function createSale($sale)
    {
        $header = [
            'Content-Type: application/json',
            "Authorization: Bearer " . $_SESSION['access_token']
        ];
        $endpoint = self::URL . "v1/sales";
        return CurlService::post($endpoint, $sale, $header, false);
    }


    static function csrf()
    {
        if (function_exists('mcrypt_create_iv'))
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return bin2hex(openssl_random_pseudo_bytes(32));
    }


}
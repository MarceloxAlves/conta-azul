<?php

class CurlService
{
    public static function get($url,  $decode = true)
    {

        $url = str_replace(' ', '%20', $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $object = curl_exec($ch);

        if ($object === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        if ($decode)
            $object = json_decode($object);

        curl_close($ch);

        return $object;
    }

    public static function post($url, $dados = [], $header = null, $decode = true)
    {

        $url = str_replace(' ', '%20', $url);

        $ch = curl_init();
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($dados));

        $object = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($object === false) {
            var_dump('Error: (' . $http_status . ') ' . curl_error($ch). $object);
            echo "</br></br>";
            return null;
        }

        if ($decode)
            $object = json_decode($object);

        curl_close($ch);

        return $object;
    }
}

<?php

class CurlService
{
    public static function get($url, $dados = [], $decode = true)
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
        $object = curl_exec($ch);

        dd($object);
        if ($object === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        if ($decode)
            $object = json_decode($object);

        curl_close($ch);

        return $object;
    }
}
